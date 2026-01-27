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

    public function getData(string $scope): Collection
    {
        $startDate = match ($scope) {
            'last_24h' => Carbon::now()->subDay(),
            'last_7d' => Carbon::now()->subDays(7),
            'last_30d' => Carbon::now()->subDays(30),
            'last_3m' => Carbon::now()->subMonths(3),
            'last_6m' => Carbon::now()->subMonths(6),
            'last_1y' => Carbon::now()->subYear(),
            default => Carbon::now()->subDays(7),
        };

        return Stay::with(['resident', 'location'])
            ->where('check_in_date', '>=', $startDate)
            ->latest()
            ->get()
            ->map(fn($s) => [
                'Giriş Tarihi' => $s->check_in_date ? $s->check_in_date->format('d.m.Y') : '-',
                'Misafir' => ($s->resident->first_name ?? '') . ' ' . ($s->resident->last_name ?? ''),
                'Lokasyon' => $s->location->name ?? '-',
                'Durum' => $s->check_out_date ? 'Çıkış Yapıldı' : 'Konaklıyor',
                'Çıkış Tarihi' => $s->check_out_date ? $s->check_out_date->format('d.m.Y') : '-',
            ]);
    }

    public function getHeaders(): array
    {
        return ['Giriş Tarihi', 'Misafir', 'Lokasyon', 'Durum', 'Çıkış Tarihi'];
    }
}