<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\Stay;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class StayManagementReport implements ReportInterface
{
    public function getName(): string
    {
        return "Misafirhane Konaklama Hareketleri Raporu";
    }

    public function getData(string $scope): \Illuminate\Support\Collection
    {
        \Log::info("Rapor Scope: " . $scope);

        // Başlangıç tarihini hesapla
        $startDate = match ($scope) {
            'last_24h' => now()->subDay(),
            'last_7d' => now()->subDays(7),
            'last_30d' => now()->subDays(30),
            'last_3m' => now()->subMonths(3),
            'last_6m' => now()->subMonths(6),
            'last_1y' => now()->subYear(),
            default => now()->subYears(2),
        };

        // DB::raw kullanarak veritabanı seviyesinde tarih dönüşümü yapalım
        // Bu, sütun tipi ne olursa olsun (string/datetime) karşılaştırmayı zorlar
        $data = \App\Models\Stay::with(['resident', 'location'])
            ->whereRaw("STR_TO_DATE(check_in_date, '%Y-%m-%d') >= ?", [$startDate->format('Y-m-d')])
            ->latest()
            ->get();

        \Log::info("Sorgulanan Tarih: " . $startDate->format('Y-m-d'));
        \Log::info("Bulunan Kayıt Sayısı: " . $data->count());

        // Eğer hala 0 çıkıyorsa, test amaçlı tüm verileri çekip çekmediğine bakalım
        if ($data->isEmpty()) {
            $totalCount = \App\Models\Stay::count();
            \Log::warning("Filtreli sonuç 0 ama tabloda toplam {$totalCount} kayıt var.");
        }

        return $data->map(fn($s) => [
            'Giriş Tarihi' => $s->check_in_date ? \Carbon\Carbon::parse($s->check_in_date)->format('d.m.Y') : '-',
            'Misafir' => ($s->resident->first_name ?? '') . ' ' . ($s->resident->last_name ?? ''),
            'Lokasyon' => $s->location->name ?? '-',
            'Durum' => $s->check_out_date ? 'Çıkış Yapıldı' : 'Konaklıyor',
            'Çıkış Tarihi' => $s->check_out_date ? \Carbon\Carbon::parse($s->check_out_date)->format('d.m.Y') : '-',
        ]);
    }

    public function getHeaders(): array
    {
        return ['Giriş Tarihi', 'Misafir', 'Lokasyon', 'Durum', 'Çıkış Tarihi'];
    }
}