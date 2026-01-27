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
        // Hangi scope gelmiş loglayalım (Hata ayıklamak için)
        \Log::info("Rapor Scope: " . $scope);

        $startDate = match ($scope) {
            'last_24h' => now()->subDay(),
            'last_7d' => now()->subDays(7),
            'last_30d' => now()->subDays(30),
            'last_3m' => now()->subMonths(3),
            'last_6m' => now()->subMonths(6),
            'last_1y' => now()->subYear(), // Parametresiz kullanım
            default => now()->subYears(5), // Hatalı yer burasıydı, subYears(5) yaptık
        };

        $query = \App\Models\Stay::with(['resident', 'location'])
            ->where('check_in_date', '>=', $startDate->format('Y-m-d H:i:s'));

        $data = $query->latest()->get();

        // Log ile kontrol
        \Log::info("Başlangıç Tarihi: " . $startDate->toDateTimeString());
        \Log::info("Bulunan Kayıt Sayısı: " . $data->count());

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