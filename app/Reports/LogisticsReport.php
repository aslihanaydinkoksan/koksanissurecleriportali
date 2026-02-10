<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\Shipment;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class LogisticsReport implements ReportInterface
{
    public function getName(): string
    {
        return "Lojistik: Sevkiyat ve Araç Operasyon Raporu";
    }

    public function getData(string $frequency): Collection
    {
        // Tarih aralığını akıcı (fluent) Carbon metodlarıyla belirliyoruz
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

        // Eager Loading ile ilişkili verileri çekiyoruz
        return Shipment::with('businessUnit')
            ->where('created_at', '>=', $startDate)
            ->orderBy('cikis_tarihi', 'desc')
            ->get()
            ->map(fn($s) => [
                'Kayıt No' => $s->id,
                'Önem' => $s->is_important ? '⚠️ KRİTİK' : 'Normal',
                'İş Birimi' => $s->businessUnit?->name ?? 'Merkez',
                'Statü' => $this->translateStatus($s->shipment_status),
                'Tür' => $this->translateType($s->shipment_type),
                'Araç/Gemi' => $s->arac_tipi === 'Gemi' ? "Gemi: {$s->gemi_adi}" : "{$s->arac_tipi} ({$s->plaka})",
                'Sürücü' => $s->sofor_adi ?? '-',
                'Güzergah' => ($s->kalkis_noktasi ?? $s->kalkis_limani) . ' ➡️ ' . ($s->varis_noktasi ?? $s->varis_limani),
                'Kargo İçeriği' => $s->kargo_icerigi,
                'Miktar' => $s->kargo_miktari . ' ' . $s->kargo_tipi,
                'Çıkış Tarihi' => $s->cikis_tarihi ? Carbon::parse($s->cikis_tarihi)->format('d.m.Y H:i') : '-',
                'Tahmini Varış' => $s->tahmini_varis_tarihi ? Carbon::parse($s->tahmini_varis_tarihi)->format('d.m.Y') : '-',
            ]);
    }

    public function getHeaders(): array
    {
        return [
            'Kayıt No',
            'Önem Seviyesi',
            'İş Birimi',
            'Sevkiyat Durumu',
            'Sevkiyat Türü',
            'Araç Bilgisi',
            'Sürücü Adı',
            'Güzergah (Kalkış-Varış)',
            'Kargo İçeriği',
            'Miktar/Birim',
            'Çıkış Tarihi',
            'Tahmini Varış'
        ];
    }

    private function translateType(?string $type): string
    {
        return match ($type) {
            'import' => 'İthalat',
            'export' => 'İhracat',
            default => $type ?? 'Bilinmiyor'
        };
    }

    private function translateStatus(?string $status): string
    {
        return match ($status) {
            'pending' => 'Beklemede',
            'approved' => 'Onaylandı',
            'on_road' => 'Yolda',
            'delivered' => 'Teslim Edildi',
            'cancelled' => 'İptal Edildi',
            default => $status ?? 'Bilinmiyor'
        };
    }
}