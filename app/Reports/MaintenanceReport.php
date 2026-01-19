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

    /**
     * Bakım verilerini belirlenen frekansa göre filtreler.
     */
    public function getData(string $frequency): Collection
    {
        // UI tarafındaki select değerleriyle tam uyumlu match yapısı
        $startDate = match ($frequency) {
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subDays(7),
            'monthly' => Carbon::now()->subMonth(),
            'last_3_months' => Carbon::now()->subMonths(3),
            'last_6_months' => Carbon::now()->subMonths(6),
            'yearly' => Carbon::now()->subYear(),
            'minute' => Carbon::now()->subMinutes(2), // Test amaçlı
            default => Carbon::now()->subDays(7),
        };

        // İlişkili modellerle (Asset, Type, User, BusinessUnit) Eager Loading yapıyoruz
        return MaintenancePlan::with(['asset', 'type', 'user', 'businessUnit'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get()
            ->map(fn($p) => [
                'Bakım No' => $p->id,
                'İş Birimi' => $p->businessUnit?->name ?? 'Merkez',
                'Varlık / Makine' => ($p->asset?->name ?? 'Bilinmeyen') . ' (' . ($p->asset?->serial_number ?? '-') . ')',
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

    /**
     * Veritabanı statülerini kullanıcı dostu metne çevirir.
     */
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

    /**
     * Öncelik seviyelerini görselleştirilmiş metne çevirir.
     */
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