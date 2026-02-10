<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ScheduledReport;
use App\Exports\DynamicReportExport;
use App\Mail\DynamicReportMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;
use Illuminate\Support\Str;

class SendScheduledReports extends Command
{
    protected $signature = 'reports:send-scheduled';
    protected $description = 'Zamanlanmış raporları kontrol eder ve gönderir.';

    public function handle()
    {
        $now = Carbon::now();
        $currentTime = $now->format('H:i');

        // Aktif raporları getir
        $reports = ScheduledReport::where('is_active', true)->get();

        foreach ($reports as $report) {
            if ($this->shouldSend($report, $now)) {
                $this->processReport($report);
            }
        }
    }

    /**
     * Raporun gönderilme vaktinin gelip gelmediğini kontrol eder.
     */
    private function shouldSend($report, $now)
    {
        $currentTime = $now->format('H:i');

        // Zaman kontrolü (send_time veritabanında H:i formatında olmalı)
        if (substr($report->send_time, 0, 5) !== $currentTime && $report->frequency !== 'minute') {
            return false;
        }

        // Frekans ve son gönderim tarihi kontrolü
        switch ($report->frequency) {
            case 'minute':
                return true; // Test amaçlı her dakika
            case 'daily':
                return !$report->last_sent_at || !$report->last_sent_at->isToday();
            case 'weekly':
                return $now->isMonday() && (!$report->last_sent_at || !$report->last_sent_at->isCurrentWeek());
            case 'monthly':
                return $now->day == 1 && (!$report->last_sent_at || !$report->last_sent_at->isCurrentMonth());
        }

        return false;
    }

    /**
     * Raporu oluşturur ve mail atar.
     */
    private function processReport($report)
    {
        try {
            $reportClass = $report->report_class;
            if (!class_exists($reportClass))
                return;

            $instance = new $reportClass();
            $data = $instance->getData($report->filter_frequency);
            $headers = $instance->getHeaders();

            if ($data->isEmpty()) {
                // Başlıkların karşısına "VERİ GİRİŞİ YAPILMAMIŞ" yazan bir satır oluşturuyoruz
                $warningRow = [];
                foreach ($headers as $header) {
                    $warningRow[$header] = '--- SISTEME VERI GIRISI YAPILMAMISTIR ---';
                }
                $data = collect([$warningRow]);
            }

            if (!Storage::disk('local')->exists('temp_reports')) {
                Storage::disk('local')->makeDirectory('temp_reports');
            }

            // Uzantıyı ve Export Formatını belirle
            $extension = ($report->file_format === 'pdf') ? 'pdf' : 'xlsx';
            $exportFormat = ($report->file_format === 'pdf')
                ? \Maatwebsite\Excel\Excel::DOMPDF
                : \Maatwebsite\Excel\Excel::XLSX;

            $fileName = Str::slug($report->report_name) . '_' . now()->format('Y_m_d_His') . '.' . $extension;
            $tempPath = 'temp_reports/' . $fileName;

            // Excel veya PDF'i oluştur ve geçici depola
            Excel::store(
                new \App\Exports\DynamicReportExport($data, $headers),
                $tempPath,
                'local',
                $exportFormat
            );

            if (!Storage::disk('local')->exists($tempPath)) {
                throw new \Exception("Dosya diskte oluşturulamadı: " . $tempPath);
            }

            $fullPath = storage_path('app/' . $tempPath);

            // Mail gönder (Format bilgisini de gönderiyoruz)
            Mail::to($report->recipients)->send(
                new DynamicReportMail($report->report_name, $fullPath, $fileName, $report->file_format)
            );

            $report->update(['last_sent_at' => now()]);
            Storage::delete($tempPath);

            $this->info("Rapor gönderildi ({$report->file_format}): {$report->report_name}");
        } catch (\Exception $e) {
            \Log::error("Rapor Gönderim Hatası ({$report->report_name}): " . $e->getMessage());
            $this->error("Hata: " . $e->getMessage());
        }
    }
}