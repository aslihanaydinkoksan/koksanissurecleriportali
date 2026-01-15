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

    public function getData(string $frequency): Collection
    {
        $days = match ($frequency) {
            'daily' => 1,
            'weekly' => 7,
            'monthly' => 30,
            'minute' => 2,
            default => 7
        };

        $startDate = Carbon::now()->subDays($days);

        return ProductionPlan::with(['businessUnit', 'user'])
            ->where('week_start_date', '>=', $startDate)
            ->orderBy('week_start_date', 'desc')
            ->get()
            ->map(fn($p) => [
                'Plan No' => $p->id,
                'Önem' => $p->is_important ? '⚠️ KRİTİK' : 'Normal',
                'İş Birimi' => $p->businessUnit?->name ?? 'Genel Üretim',
                // Plan başlığı dizi gelirse birleştiriyoruz
                'Plan Başlığı' => is_iterable($p->plan_title) ? implode(' - ', (array) $p->plan_title) : $p->plan_title,
                'Hafta Başı' => $p->week_start_date ? Carbon::parse($p->week_start_date)->format('d.m.Y') : '-',
                // Plan detaylarını her ihtimale karşı (array veya collection) güvenli hale getirdik
                'Plan Detayları' => $this->formatDetails($p->plan_details),
                'Sorumlu' => $p->user?->name ?? '-',
                'Kayıt Tarihi' => $p->created_at->format('d.m.Y H:i'),
            ]);
    }

    /**
     * Karmaşık veri yapılarını güvenli bir şekilde metne çevirir.
     */
    private function formatDetails($details): string
    {
        if (is_iterable($details)) {
            return collect($details)->map(function ($item, $key) {
                $no = $key + 1;
                return "{$no}) Makine: " . ($item['machine'] ?? '-') .
                    " | Ürün: " . ($item['product'] ?? '-') .
                    " | Miktar: " . ($item['quantity'] ?? '0');
            })->implode("\n--------------------------\n");
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