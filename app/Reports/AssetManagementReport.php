<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\Asset;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class AssetManagementReport implements ReportInterface
{
    public function getName(): string
    {
        return "Misafirhane Demirbaş ve Envanter Raporu";
    }

    public function getData(string $frequency): Collection
    {
        // Demirbaşlar genellikle anlık durumdur ancak scope'a göre filtre eklemek istersen:
        return Asset::with('location')
            ->latest()
            ->get()
            ->map(fn($a) => [
                'Demirbaş Adı' => $a->name,
                'Lokasyon' => $a->location->name ?? '-',
                'Adet' => $a->quantity,
                'Kayıt Tarihi' => $a->created_at->format('d.m.Y'),
            ]);
    }

    public function getHeaders(): array
    {
        return ['Demirbaş Adı', 'Lokasyon', 'Adet', 'Kayıt Tarihi'];
    }
}