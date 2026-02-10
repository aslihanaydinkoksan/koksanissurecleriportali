<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\Event;
use App\Models\Booking;
use App\Models\Travel;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class ServicesReport implements ReportInterface
{
    public function getName(): string
    {
        return "İdari İşler Operasyonel Faaliyet Raporu";
    }

    /**
     * Rapor verilerini belirlenen tarih frekansına göre sekmeler halinde döndürür.
     */
    public function getData(string $frequency): Collection
    {
        // Standart frekans anahtarlarıyla tarih aralığını belirliyoruz
        $startDate = match ($frequency) {
            'daily' => Carbon::now()->subDay(),
            'weekly' => Carbon::now()->subDays(7),
            'monthly' => Carbon::now()->subMonth(),
            'last_3_months' => Carbon::now()->subMonths(3),
            'last_6_months' => Carbon::now()->subMonths(6),
            'yearly' => Carbon::now()->subYear(),
            'minute' => Carbon::now()->subMinutes(2), // Debug amaçlı
            default => Carbon::now()->subDays(7),
        };

        return collect([
            'Etkinlikler ve Ziyaretler' => $this->getEventsData($startDate),
            'Rezervasyonlar (Bilet-Otel)' => $this->getBookingsData($startDate),
            'Seyahat Dosyaları Özet' => $this->getTravelsData($startDate),
        ]);
    }

    /**
     * SEKME 1: ETKİNLİKLER
     */
    private function getEventsData($startDate): Collection
    {
        return Event::with(['businessUnit', 'expenses', 'customer', 'media'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get()
            ->map(fn($e) => [
                'ID' => 'EVT-' . $e->id,
                'Etkinlik Türü' => $this->translateEventType($e->event_type),
                'Birim' => $e->businessUnit?->name ?? '-',
                'Önem' => $e->is_important ? '⚠️ KRİTİK' : 'Normal',
                'Başlık/Konu' => $e->title,
                'Müşteri (Cari)' => $e->customer?->name ?? '-',
                'Gerçekleşen Yer' => $e->location ?? '-',
                'Zaman Aralığı' => $this->formatDateRange($e->start_datetime, $e->end_datetime),
                'Toplam Maliyet' => $this->calculateTotalExpenses($e),
                'Durum' => $this->translateStatus($e->visit_status),
                'Ekli Dosyalar' => $this->getFileNames($e),
                'Portal Linki' => config('app.url') . "/service/events/" . $e->id,
            ]);
    }

    /**
     * SEKME 2: REZERVASYONLAR
     */
    private function getBookingsData($startDate): Collection
    {
        return Booking::with(['businessUnit', 'expenses', 'bookable.customer', 'media'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get()
            ->map(function ($b) {
                $inheritedCustomer = ($b->bookable_type === Event::class)
                    ? $b->bookable?->customer?->name
                    : '-';

                return [
                    'ID' => 'BKG-' . $b->id,
                    'Hizmet Türü' => '🎫 ' . $this->translateBookingType($b->type),
                    'Birim' => $b->businessUnit?->name ?? '-',
                    'Hizmet Sağlayıcı' => $b->provider_name ?? '-',
                    'Kalkış Noktası' => $b->origin ?? '-',
                    'Varış Noktası' => $b->destination ?? '-',
                    'Konaklama/Yer' => $b->location ?? '-',
                    'Zaman Aralığı' => $this->formatDateRange($b->start_datetime, $b->end_datetime),
                    'Maliyet' => $this->calculateTotalExpenses($b, $b->cost),
                    'PNR / Onay No' => $b->confirmation_code ?? '-',
                    'Mevcut Durum' => $this->translateStatus($b->status),
                    'Cari Müşteri' => $inheritedCustomer,
                    'Ekli Dosyalar' => $this->getFileNames($b),
                    'Portal Linki' => config('app.url') . "/bookings/" . $b->id,
                ];
            });
    }

    /**
     * SEKME 3: SEYAHATLER
     */
    private function getTravelsData($startDate): Collection
    {
        return Travel::with(['businessUnit', 'expenses', 'bookings.media', 'customerVisits.customer', 'media'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get()
            ->map(fn($t) => [
                'ID' => 'TRV-' . $t->id,
                'Seyahat Adı' => $t->name,
                'Birim' => $t->businessUnit?->name ?? '-',
                'Önem Seviyesi' => $t->is_important ? '⚠️ KRİTİK' : 'Normal',
                'Kapsamdaki Rota' => $this->aggregateLocations($t),
                'Zaman Aralığı' => $this->formatDateRange($t->start_date, $t->end_date, true),
                'Toplam Gider' => $this->calculateTotalExpenses($t),
                'Durum' => $this->translateStatus($t->status),
                'Dosya İçeriği' => $this->getDeepFileSummary($t),
                'Portal Linki' => config('app.url') . "/travels/" . $t->id,
            ]);
    }

    public function getHeaders(): array
    {
        return [
            'Etkinlikler ve Ziyaretler' => [
                'ID',
                'Etkinlik Türü',
                'Birim',
                'Önem',
                'Başlık/Konu',
                'Müşteri (Cari)',
                'Gerçekleşen Yer',
                'Zaman Aralığı',
                'Toplam Maliyet',
                'Durum',
                'Ekli Dosyalar',
                'Portal Linki'
            ],
            'Rezervasyonlar (Bilet-Otel)' => [
                'ID',
                'Hizmet Türü',
                'Birim',
                'Hizmet Sağlayıcı',
                'Kalkış Noktası',
                'Varış Noktası',
                'Konaklama/Yer',
                'Zaman Aralığı',
                'Maliyet',
                'PNR / Onay No',
                'Mevcut Durum',
                'Cari Müşteri',
                'Ekli Dosyalar',
                'Portal Linki'
            ],
            'Seyahat Dosyaları Özet' => [
                'ID',
                'Seyahat Adı',
                'Birim',
                'Önem Seviyesi',
                'Kapsamdaki Rota',
                'Zaman Aralığı',
                'Toplam Gider',
                'Durum',
                'Dosya İçeriği',
                'Portal Linki'
            ]
        ];
    }

    // --- YARDIMCI METOTLAR (Mimari Tutarlılık İçin Korunmuştur) ---

    private function formatDateRange($start, $end, $isDateOnly = false): string
    {
        if (!$start)
            return '-';
        $format = $isDateOnly ? 'd.m.Y' : 'd.m.Y H:i';
        $startStr = Carbon::parse($start)->format($format);
        $endStr = $end ? Carbon::parse($end)->format($format) : '...';
        return "{$startStr} - {$endStr}";
    }

    private function calculateTotalExpenses($model, $directCost = null): string
    {
        if ($model->expenses && $model->expenses->count() > 0) {
            return $model->expenses->groupBy('currency')
                ->map(fn($group, $curr) => number_format($group->sum('amount'), 2) . ' ' . $curr)
                ->implode(' + ');
        }
        return $directCost ? number_format((float) $directCost, 2) . ' TRY' : '0.00 TRY';
    }

    private function translateStatus(?string $status): string
    {
        if (!$status)
            return 'Belirtilmedi';
        return match (strtolower(trim($status))) {
            'planned', 'planlandi', 'planlandı' => '🟢 Planlandı',
            'waiting', 'pending', 'beklemede' => '🟡 Beklemede',
            'in_progress', 'devam ediyor' => '🔵 Devam Ediyor',
            'completed', 'done', 'tamamlandı' => '✅ Tamamlandı',
            'approved', 'confirmed', 'onaylandı' => '💎 Onaylandı',
            'cancelled', 'rejected', 'iptal' => '🔴 İptal Edildi',
            default => ucfirst($status)
        };
    }

    private function translateEventType(?string $type): string
    {
        return match ($type) {
            'meeting', 'musteri_karsilama' => '🤝 Toplantı',
            'fair' => '🎪 Fuar Katılımı',
            'training' => '🎓 Eğitim',
            'visit', 'musteri_ziyareti' => '🚗 Müşteri Ziyareti',
            default => ucfirst($type ?? 'Etkinlik')
        };
    }

    private function translateBookingType(?string $type): string
    {
        return match ($type) {
            'flight' => 'Uçak Bileti',
            'hotel' => 'Otel Konaklama',
            'car' => 'Araç Kiralama',
            'train' => 'Tren/Raylı Sistem',
            default => ucfirst($type ?? 'Rezervasyon')
        };
    }

    private function aggregateLocations($travel): string
    {
        $locs = collect();
        $travel->bookings->each(fn($b) => $locs->push($b->location));
        $travel->customerVisits->each(fn($v) => $locs->push($v->location));
        return $locs->filter()->unique()->implode(', ') ?: '-';
    }

    private function getFileNames($model): string
    {
        if (!$model->media || $model->media->isEmpty())
            return "Dosya Yok";
        return $model->media->map(fn($m) => $m->file_name . " (" . $m->getFullUrl() . ")")->implode("\n");
    }

    private function getDeepFileSummary($travel): string
    {
        $allFiles = collect();
        if ($travel->media) {
            $travel->media->each(fn($m) => $allFiles->push($m->file_name . " (" . $m->getFullUrl() . ")"));
        }
        $travel->bookings->each(function ($b) use ($allFiles) {
            if ($b->media)
                $b->media->each(fn($m) => $allFiles->push($m->file_name . " (" . $m->getFullUrl() . ")"));
        });
        $result = $allFiles->unique();
        return $result->isEmpty() ? "Dosya Yok" : $result->implode("\n");
    }
}