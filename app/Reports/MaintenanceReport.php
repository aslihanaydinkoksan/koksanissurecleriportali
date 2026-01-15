<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\MaintenancePlan;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class MaintenanceReport implements ReportInterface
{
    public function getName(): string
    {
        return "Bakım: Arıza ve Periyodik Bakım Takip Raporu";
    }

    public function getData(string $frequency): Collection
    {
        // Frekansa göre filtreleme mantığı
        $days = match ($frequency) {
            'daily' => 1,
            'weekly' => 7,
            'monthly' => 30,
            'minute' => 2,
            default => 7
        };

        $startDate = Carbon::now()->subDays($days);

        // İlişkili modellerle (Asset, Type, User, BusinessUnit) veriyi çekiyoruz
        return MaintenancePlan::with(['asset', 'type', 'user', 'businessUnit'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get()
            ->map(fn($p) => [
                'Bakım No' => $p->id,
                'İş Birimi' => $p->businessUnit?->name ?? 'Merkez',
                'Varlık / Makine' => $p->asset?->name . ' (' . ($p->asset?->serial_number ?? '-') . ')',
                'Bakım Türü' => $p->type?->name ?? 'Belirtilmedi',
                'Başlık' => $p->title,
                'Öncelik' => $this->translatePriority($p->priority),
                'Durum' => $this->translateStatus($p->status),
                'Planlanan Başl.' => $p->planned_start_date ? Carbon::parse($p->planned_start_date)->format('d.m.Y H:i') : '-',
                'Gerçekleşen Başl.' => $p->actual_start_date ? Carbon::parse($p->actual_start_date)->format('d.m.Y H:i') : 'Henüz Başlamadı',
                'Sorumlu' => $p->user?->name ?? '-',
                'Kapatma Notu' => $p->completion_note ? strip_tags($p->completion_note) : '-',
                'Kayıt Tarihi' => $p->created_at->format('d.m.Y H:i'),
            ]);
    }

    public function getHeaders(): array
    {
        return [
            'Bakım No',
            'İş Birimi',
            'Ekipman / Seri No',
            'Bakım Tipi',
            'İş Başlığı',
            'Önem Derecesi',
            'Güncel Durum',
            'Planlanan Tarih',
            'Gerçekleşme Tarihi',
            'Sorumlu Personel',
            'Sonuç / Kapatma Notu',
            'Oluşturulma Tarihi'
        ];
    }

    private function translateStatus(?string $status): string
    {
        return match ($status) {
            'pending' => 'Beklemede',
            'in_progress' => 'Devam Ediyor',
            'completed' => 'Tamamlandı ✅',
            'cancelled' => 'İptal Edildi',
            'on_hold' => 'Durduruldu',
            default => $status ?? 'Bilinmiyor'
        };
    }

    private function translatePriority(?string $priority): string
    {
        return match ($priority) {
            'low' => 'Düşük',
            'medium' => 'Orta',
            'high' => 'Yüksek',
            'urgent' => '⚠️ ACİL',
            'critical' => '🚨 KRİTİK',
            default => $priority ?? 'Normal'
        };
    }
}