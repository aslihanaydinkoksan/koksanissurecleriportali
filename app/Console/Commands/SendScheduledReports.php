<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ReportSchedule;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Exports\DynamicReportExport;
use Maatwebsite\Excel\Facades\Excel;

class SendScheduledReports extends Command
{
    protected $signature = 'reports:send';
    protected $description = 'Zamanlanmış e-posta raporlarını dinamik olarak gönderir.';

    public function handle()
    {
        $schedules = \App\Models\ReportSchedule::where('is_active', true)->get();

        if ($schedules->isEmpty()) {
            $this->info("Aktif bir rapor planı bulunamadı.");
            return;
        }

        foreach ($schedules as $schedule) {
            if ($this->shouldSend($schedule)) {

                $reportClass = $schedule->report_type;
                if (!class_exists($reportClass)) {
                    $this->error("Rapor sınıfı bulunamadı: {$reportClass}");
                    continue;
                }

                $report = new $reportClass();
                $data = $report->getData($schedule->data_scope);
                $headers = $report->getHeaders();

                // Dosya adını temizle (Slug)
                $baseFileName = str($report->getName())->slug('_') . '_' . now()->format('d_m_Y_H_i');
                $extension = ($schedule->file_format == 'pdf') ? '.pdf' : '.xlsx';
                $fileName = $baseFileName . $extension;

                // Klasörün varlığından emin ol (Laravel Disk üzerinden)
                if (!\Illuminate\Support\Facades\Storage::disk('local')->exists('reports')) {
                    \Illuminate\Support\Facades\Storage::disk('local')->makeDirectory('reports');
                }

                // Göreli yol (local disk içinde)
                $relativeFilePath = 'reports/' . $fileName;

                // Windows uyumlu tam yol (Laravel otomatik halleder)
                $fullPath = \Illuminate\Support\Facades\Storage::disk('local')->path($relativeFilePath);

                // FORMAT SEÇİMİ VE DOSYA OLUŞTURMA
                if ($schedule->file_format == 'pdf') {
                    $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('emails.dynamic_report', [
                        'reportName' => $report->getName(),
                        'data' => $data,
                        'headers' => $headers,
                        'frequency' => $schedule->frequency
                    ])->setPaper('a4', 'landscape');

                    // PDF'i diske kaydet
                    \Illuminate\Support\Facades\Storage::disk('local')->put($relativeFilePath, $pdf->output());
                } else {
                    // Excel'i diske kaydet
                    \Maatwebsite\Excel\Facades\Excel::store(
                        new \App\Exports\DynamicReportExport($data, $headers),
                        $relativeFilePath,
                        'local'
                    );
                }

                // Dosyanın gerçekten orada olduğunu teyit et
                if (!\Illuminate\Support\Facades\Storage::disk('local')->exists($relativeFilePath)) {
                    $this->error("Dosya diskte bulunamadı: {$relativeFilePath}");
                    continue;
                }

                // MAIL GÖNDERİMİ
                \Illuminate\Support\Facades\Mail::send('emails.dynamic_report', [
                    'reportName' => $report->getName(),
                    'data' => $data,
                    'headers' => $headers,
                    'frequency' => $schedule->frequency
                ], function ($message) use ($schedule, $report, $fullPath) {
                    $message->to($schedule->recipients)
                        ->subject($report->getName() . ' - ' . date('d.m.Y'))
                        ->attach($fullPath);
                });

                // Log ve Güncelleme
                $reportScheduleModel = \App\Models\ReportSchedule::find($schedule->id);
                if ($reportScheduleModel) {
                    $reportScheduleModel->update(['last_sent_at' => now()]);
                }

                // Gönderim sonrası temizlik
                \Illuminate\Support\Facades\Storage::disk('local')->delete($relativeFilePath);

                $this->info("Gönderildi: " . $report->getName() . " (" . strtoupper($schedule->file_format) . ")");
            }
        }
    }

    private function shouldSend($schedule)
    {
        // Eğer hiç gönderilmemişse veya test modundaysa (every_minute) gönder
        if (!$schedule->last_sent_at || $schedule->frequency === 'every_minute') {
            return true;
        }

        $lastSent = Carbon::parse($schedule->last_sent_at);
        $now = now();

        return match ($schedule->frequency) {
            'daily_morning' => $lastSent->diffInHours($now) >= 23 && $now->hour >= 9,
            'daily_evening' => $lastSent->diffInHours($now) >= 23 && $now->hour >= 18,
            'weekly_monday' => $now->isMonday() && $lastSent->diffInDays($now) >= 6,
            'monthly_first' => $now->day === 1 && $lastSent->diffInDays($now) >= 27,
            default => false,
        };
    }
}