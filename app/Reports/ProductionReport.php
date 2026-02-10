<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\ProductionPlan;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ProductionReport implements ReportInterface
{
    public function getName(): string
    {
        return "Üretim: Haftalık Planlama ve Detay Raporu";
    }

    /**
     * Üretim planlarını belirlenen tarih frekansına göre getirir.
     */
    public function getData(string $frequency): Collection
    {
        // Standartlaştırılmış tarih aralığı hesaplama mantığı
        $startDate = match ($frequency) {
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subDays(7),
            'monthly' => Carbon::now()->subMonth(),
            'last_3_months' => Carbon::now()->subMonths(3),
            'last_6_months' => Carbon::now()->subMonths(6),
            'yearly' => Carbon::now()->subYear(),
            'minute' => Carbon::now()->subMinutes(2), // Debug/Test amaçlı
            default => Carbon::now()->subDays(7),
        };

        // Üretim planları 'week_start_date' üzerinden süzülür
        return ProductionPlan::with(['businessUnit', 'user'])
            ->where('week_start_date', '>=', $startDate)
            ->orderBy('week_start_date', 'desc')
            ->get()
            ->map(fn($p) => [
                'Plan No' => $p->id,
                'Önem' => $p->is_important ? '⚠️ KRİTİK' : 'Normal',
                'İş Birimi' => $p->businessUnit?->name ?? 'Genel Üretim',
                'Plan Başlığı' => is_iterable($p->plan_title) ? implode(' - ', (array) $p->plan_title) : $p->plan_title,
                'Hafta Başı' => $p->week_start_date ? Carbon::parse($p->week_start_date)->format('d.m.Y') : '-',
                'Plan Detayları' => $this->formatDetails($p->plan_details),
                'Sorumlu' => $p->user?->name ?? '-',
                'Kayıt Tarihi' => $p->created_at->format('d.m.Y H:i'),
            ]);
    }

    /**
     * Karmaşık JSON veri yapılarını (makine, ürün, miktar) okunabilir metne dönüştürür.
     */
    private function formatDetails($details): string
    {
        if (is_iterable($details)) {
            return collect($details)->map(function ($item, $key) {
                $no = $key + 1;
                return "{$no}) Makine: " . ($item['machine'] ?? '-') .
                    " | Ürün: " . ($item['product'] ?? '-') .
                    " | Miktar: " . ($item['quantity'] ?? '0');
            })->implode("\n" . str_repeat('-', 20) . "\n");
        }
        return strip_tags((string) ($details ?? '-'));
    }

    public function getHeaders(): array
    {
        return [
            'Plan No',
            'Önem Seviyesi',
            'İş Birimi',
            'Plan Başlığı',
            'Hafta Başlangıç Tarihi',
            'Plan Detayları / Notlar',
            'Planlayan Kullanıcı',
            'Sisteme Giriş Tarihi'
        ];
    }
}