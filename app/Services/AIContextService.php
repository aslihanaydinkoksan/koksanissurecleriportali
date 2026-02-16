<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Event;
use App\Models\Todo;
use App\Models\Shipment;
use App\Models\MaintenancePlan;
use App\Models\ProductionPlan;
use App\Models\Booking;
use App\Models\Travel;

class AIContextService
{
    /**
     * Tüm modüllerden AI için özet veri toplar ve yapay zekanın anlayacağı formata çevirir.
     */
    public function getAggregatedContext(): array
    {
        $user = Auth::user();
        $today = Carbon::today();

        return [
            'user_name'    => $user->name,
            'active_unit'  => session('active_unit_name', 'Genel'),
            'roles'        => $user->getRoleNames()->implode(', '),
            'events'       => $this->getUpcomingEvents($user, $today),
            'todos'        => $this->getPendingTodos($user),
            'shipments'    => $this->getUpcomingShipments($user, $today),
            'maintenances' => $this->getUpcomingMaintenances($user, $today),
            'productions'  => $this->getUpcomingProductionPlans($user, $today),
            'bookings'     => $this->getUpcomingBookings($user, $today),
            'travels'      => $this->getUpcomingTravels($user, $today),
        ];
    }

    // 1. ETKİNLİKLER (Events)
    private function getUpcomingEvents($user, $date)
    {
        return Event::where('user_id', $user->id)
            ->whereDate('start_datetime', '>=', $date)
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get()
            ->map(fn($i) => "Tarih: " . Carbon::parse($i->start_datetime)->format('d.m H:i') . " | Konu: {$i->title}")
            ->toArray();
    }

    // 2. GÖREVLER (Todos)
    private function getPendingTodos($user)
    {
        return Todo::where('user_id', $user->id)
            ->where('is_completed', false)
            ->orderBy('due_date', 'asc')
            ->take(5)
            ->get()
            ->map(fn($i) => "Son Tarih: " . ($i->due_date ? Carbon::parse($i->due_date)->format('d.m') : '-') . " | Görev: {$i->title}")
            ->toArray();
    }

    // 3. LOJİSTİK VE SEVKİYAT (Shipments)
    private function getUpcomingShipments($user, $date)
    {
        // 'cikis_tarihi' esas alındı.
        return Shipment::whereDate('cikis_tarihi', '>=', $date)
            ->where('shipment_status', '!=', 'delivered') // Statü kontrolü (Veritabanındaki değerlerine göre revize edilebilir)
            ->orderBy('cikis_tarihi', 'asc')
            ->take(5)
            ->get()
            ->map(fn($i) => sprintf(
                "Çıkış: %s | Araç: %s | Rota: %s -> %s | Şoför: %s",
                Carbon::parse($i->cikis_tarihi)->format('d.m'),
                $i->plaka ?? 'Belirtilmemiş',
                $i->kalkis_noktasi ?? '?',
                $i->varis_noktasi ?? '?',
                $i->sofor_adi ?? '-'
            ))
            ->toArray();
    }

    // 4. BAKIM YÖNETİMİ (Maintenance Plans)
    private function getUpcomingMaintenances($user, $date)
    {
        // 'planned_start_date' esas alındı.
        return MaintenancePlan::whereDate('planned_start_date', '>=', $date)
            ->where('status', '!=', 'completed') // Enum değerine göre 'completed' veya 'finished' olabilir, kontrol et.
            ->orderBy('planned_start_date', 'asc')
            ->take(5)
            ->get()
            ->map(fn($i) => sprintf(
                "Bakım Tarihi: %s | Başlık: %s | Öncelik: %s",
                Carbon::parse($i->planned_start_date)->format('d.m'),
                $i->title,
                $i->priority ?? 'Normal'
            ))
            ->toArray();
    }

    // 5. ÜRETİM PLANLARI (Production Plans)
    private function getUpcomingProductionPlans($user, $date)
    {
        // 'week_start_date' esas alındı.
        return ProductionPlan::whereDate('week_start_date', '>=', $date)
            ->orderBy('week_start_date', 'asc')
            ->take(3) // Üretim planları uzun olabilir, az sayıda çekiyoruz.
            ->get()
            ->map(fn($i) => sprintf(
                "Hafta Başı: %s | Plan: %s | Önemli: %s",
                Carbon::parse($i->week_start_date)->format('d.m.Y'),
                $i->plan_title,
                $i->is_important ? 'Evet' : 'Hayır'
            ))
            ->toArray();
    }

    // 6. REZERVASYONLAR (Bookings)
    private function getUpcomingBookings($user, $date)
    {
        // 'start_datetime' esas alındı.
        return Booking::where('user_id', $user->id)
            ->whereDate('start_datetime', '>=', $date)
            ->orderBy('start_datetime', 'asc')
            ->take(5)
            ->get()
            ->map(fn($i) => sprintf(
                "Tarih: %s | Tür: %s | Sağlayıcı: %s | Lokasyon: %s | Kod: %s",
                Carbon::parse($i->start_datetime)->format('d.m H:i'),
                $i->type,
                $i->provider_name ?? '-',
                $i->location ?? $i->destination ?? '-', // Location yoksa destination'ı göster
                $i->confirmation_code ?? '-'
            ))
            ->toArray();
    }

    // 7. SEYAHATLER (Travels)
    private function getUpcomingTravels($user, $date)
    {
        // 'start_date' esas alındı.
        return Travel::where('user_id', $user->id)
            ->whereDate('start_date', '>=', $date)
            ->orderBy('start_date', 'asc')
            ->take(5)
            ->get()
            ->map(fn($i) => sprintf(
                "Seyahat: %s | İsim: %s | Konum: %s | Durum: %s",
                Carbon::parse($i->start_date)->format('d.m.Y'),
                $i->name,
                $i->location,
                $i->status
            ))
            ->toArray();
    }
}