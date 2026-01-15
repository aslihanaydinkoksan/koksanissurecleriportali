<?php

namespace App\Reports;

use App\Contracts\ReportInterface;
use App\Models\CustomerVisit;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class CustomerManagementReport implements ReportInterface
{
    public function getName(): string
    {
        return " Müşteri Ziyaretleri ve Makine Faaliyet Raporu";
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

        // İlişkili modellerle birlikte (Eager Loading) veriyi çekiyoruz
        return CustomerVisit::with(['customer', 'machine', 'businessUnit'])
            ->where('created_at', '>=', $startDate)
            ->latest()
            ->get()
            ->map(fn($v) => [
                'Ziyaret No' => $v->id,
                'İş Birimi' => $v->businessUnit?->name ?? 'Merkez',
                'Müşteri Adı' => $v->customer?->name ?? 'Bilinmeyen Müşteri',
                'İlgili Kişi' => $v->customer?->contact_person ?? '-',
                'Makine Model' => $v->machine?->model ?? '-',
                'Seri No' => $v->machine?->serial_number ?? '-',
                'Ziyaret Amacı' => $this->translateVisitPurpose($v->visit_purpose),
                'Teknik Notlar' => $v->after_sales_notes ? strip_tags($v->after_sales_notes) : '-',
                'Müşteri Adresi' => $v->customer?->address ?? '-',
                'İletişim' => $v->customer?->phone ?? '-',
                'Ziyaret Tarihi' => $v->created_at->format('d.m.Y H:i'),
            ]);
    }
    private function translateVisitPurpose(?string $purpose): string
    {
        return match (strtolower($purpose ?? '')) {
            'pazarlama' => 'Pazarlama Faaliyeti',
            'satis_sonrasi_hizmet' => 'Satış Sonrası Hizmet',
            'sikayet' => 'Müşteri Şikayeti',
            'tahsilat' => 'Tahsilat Görüşmesi',
            default => ucfirst(str_replace('_', ' ', $purpose ?? 'Genel Görüşme'))
        };
    }

    public function getHeaders(): array
    {
        return [
            'Ziyaret No',
            'İş Birimi',
            'Müşteri / Firma Adı',
            'Müşteri Yetkilisi',
            'Makine Modeli',
            'Makine Seri No',
            'Ziyaret Nedeni',
            'Satış Sonrası / Teknik Notlar',
            'Müşteri Lokasyonu',
            'Telefon',
            'Kayıt Tarihi'
        ];
    }
}