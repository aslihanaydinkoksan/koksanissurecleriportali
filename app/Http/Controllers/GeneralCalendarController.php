<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Http\Controllers\EventController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Auth;


class GeneralCalendarController extends Controller
{
    /**
     * FullCalendar için olay verilerini JSON formatında döndürür.
     */
    public function getEvents(Request $request)
    {
        // === GÜNCELLEME: Kullanıcı ve Rol Kontrolü ===
        $user = Auth::user();

        // Kullanıcı giriş yapmamışsa (ola ki)
        if (!$user) {
            return response()->json([]);
        }

        // Admin/Yönetici mi? Bu kontrolü en başta bir kez yapalım.
        $isAdminOrManager = in_array($user->role, ['admin', 'yönetici']);
        // === GÜNCELLEME BİTİŞİ ===


        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        $events = [];

        // 1. Sevkiyat Varışları (Lojistik)
        // === GÜNCELLEME: 'user' ilişkisini de ekleyelim (oluşturanı bilmek için) ===
        // Not: Bu, Shipment modelinizde 'user' (belongsTo) ilişkisinin tanımlı olduğunu varsayar.
        $shipments = Shipment::with(['onaylayanKullanici', 'user'])
            ->whereNotNull('tahmini_varis_tarihi')
            ->whereBetween('tahmini_varis_tarihi', [$start, $end])
            ->get();

        foreach ($shipments as $shipment) {
            $cikisTarihi = null;
            $varisTarihi = null;
            try {
                if ($shipment->cikis_tarihi) $cikisTarihi = Carbon::parse($shipment->cikis_tarihi);
                if ($shipment->tahmini_varis_tarihi) $varisTarihi = Carbon::parse($shipment->tahmini_varis_tarihi);
            } catch (\Exception $e) { /* Hatalı tarihi yoksay */
            }

            $normalizedKargo = $this->normalizeCargoContent($shipment->kargo_icerigi);
            $normalizedAracTipi = $this->normalizeVehicleType($shipment->arac_tipi);

            // === GÜNCELLEME: "Sahip" Yetki Kontrolü (Sevkiyat) ===
            // Admin/Yönetici VEYA bu kaydı oluşturan kullanıcı mı?
            $canManageThis = $isAdminOrManager || $user->id === $shipment->user_id;

            $extendedProps = [
                'eventType' => 'shipment',
                'title' => '🚚 Sevkiyat Detayı: ' . $normalizedKargo,
                'id' => $shipment->id,

                // === GÜNCELLEME: Yetkiyi Uygula (Sevkiyat) ===
                'editUrl' => $canManageThis ? route('shipments.edit', $shipment->id) : null,
                'deleteUrl' => $canManageThis ? route('shipments.destroy', $shipment->id) : null,
                'onayUrl' => $canManageThis ? route('shipments.onayla', $shipment->id) : null,
                'onayKaldirUrl' => $canManageThis ? route('shipments.onayiGeriAl', $shipment->id) : null,
                // === GÜNCELLEME BİTİŞİ ===

                'exportUrl' => route('shipments.export', $shipment->id), // Excel'i herkes alabilir

                'details' => [
                    // ... (tüm 'details' içeriği aynı kalır) ...
                    'Araç Tipi' => $normalizedAracTipi,
                    'Plaka' => $shipment->plaka,
                    'Dorse Plakası' => $shipment->dorse_plakasi,
                    'Şoför Adı' => $shipment->sofor_adi,
                    'IMO Numarası' => $shipment->imo_numarasi,
                    'Gemi Adı' => $shipment->gemi_adi,
                    'Kalkış Limanı' => $shipment->kalkis_limani,
                    'Varış Limanı' => $shipment->varis_limani,
                    'Kalkış Noktası' => $shipment->kalkis_noktasi,
                    'Varış Noktası' => $shipment->varis_noktasi,
                    'Sevkiyat Türü' => $shipment->shipment_type === 'import' ? 'İthalat' : 'İhracat',
                    'Kargo Yükü' => $normalizedKargo,
                    'Kargo Tipi' => $shipment->kargo_tipi,
                    'Kargo Miktarı' => $shipment->kargo_miktari,
                    'Çıkış Tarihi' => $cikisTarihi ? $cikisTarihi->format('d.m.Y H:i') : '-',
                    'Tahmini Varış' => $varisTarihi ? $varisTarihi->format('d.m.Y H:i') : '-',
                    'Açıklamalar' => $shipment->aciklamalar,
                    'Dosya Yolu' => $shipment->dosya_yolu ? Storage::url($shipment->dosya_yolu) : null,
                    'Onay Durumu' => $shipment->onaylanma_tarihi ? $shipment->onaylanma_tarihi->format('d.m.Y H:i') : null,
                    'Onaylayan' => $shipment->onaylayanKullanici?->name,
                ]
            ];

            $events[] = [
                'title' => 'Varış: ' . $normalizedKargo,
                'start' => $shipment->tahmini_varis_tarihi->toIso8601String(),
                'end' => $shipment->tahmini_varis_tarihi->copy()->addHours(1)->toIso8601String(),
                'color' => '#667EEA',
                'extendedProps' => $extendedProps
            ];
        }

        // 2. Üretim Planı Başlangıçları (Üretim)
        $plans = ProductionPlan::with('user') // 'user' zaten yükleniyor
            ->whereBetween('week_start_date', [$start, $end])
            ->get();

        foreach ($plans as $plan) {

            // === GÜNCELLEME: "Sahip" Yetki Kontrolü (Üretim) ===
            $canManageThis = $isAdminOrManager || $user->id === $plan->user_id;

            $events[] = [
                'title' => 'Üretim: ' . $plan->plan_title,
                'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                'end' => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
                'color' => '#4FD1C5',
                'extendedProps' => [
                    'eventType' => 'production',
                    'title' => '📅 Üretim Planı Detayı',
                    'id' => $plan->id,

                    // === GÜNCELLEME: Yetkiyi Uygula (Üretim) ===
                    'editUrl' => $canManageThis ? route('production.plans.edit', $plan->id) : null,
                    'deleteUrl' => $canManageThis ? route('production.plans.destroy', $plan->id) : null,
                    // === GÜNCELLEME BİTİŞİ ===

                    'details' => [
                        'Plan Başlığı' => $plan->plan_title,
                        'Hafta Başlangıcı' => $plan->week_start_date->format('d.m.Y'),
                        'Plan Detayları' => $plan->plan_details,
                        'Oluşturan' => $plan->user?->name,
                        'Kayıt Tarihi' => $plan->created_at->format('d.m.Y H:i'),
                    ]
                ]
            ];
        }

        // 3. Etkinlikler (Hizmet)
        $serviceEvents = Event::with('user') // 'user' zaten yükleniyor
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_datetime', [$start, $end])
                    ->orWhereBetween('end_datetime', [$start, $end])
                    ->orWhere(fn($sub) => $sub->where('start_datetime', '<=', $start)->where('end_datetime', '>=', $end));
            })->get();

        foreach ($serviceEvents as $event) {

            // === GÜNCELLEME: "Sahip" Yetki Kontrolü (Hizmet Etkinlik) ===
            $canManageThis = $isAdminOrManager || $user->id === $event->user_id;

            $events[] = [
                'title' => 'Etkinlik: ' . $event->title,
                'start' => $event->start_datetime->toIso8601String(),
                'end' => $event->end_datetime->toIso8601String(),
                'color' => '#F093FB',
                'extendedProps' => [
                    'eventType' => 'service_event',
                    'title' => '🎉 Etkinlik Detayı: ' . $event->title,
                    'id' => $event->id,

                    // === GÜNCELLEME: Yetkiyi Uygula (Hizmet Etkinlik) ===
                    'editUrl' => $canManageThis ? route('service.events.edit', $event->id) : null,
                    'deleteUrl' => $canManageThis ? route('service.events.destroy', $event->id) : null,
                    // === GÜNCELLEME BİTİŞİ ===

                    'details' => [
                        'Etkinlik Tipi' => $this->getEventTypes()[$event->event_type] ?? ucfirst($event->event_type),
                        'Konum' => $event->location,
                        'Başlangıç' => $event->start_datetime->format('d.m.Y H:i'),
                        'Bitiş' => $event->end_datetime->format('d.m.Y H:i'),
                        'Açıklama' => $event->description,
                        'Kayıt Yapan' => $event->user?->name,
                    ]
                ]
            ];
        }

        // 4. Araç Atamaları (Hizmet)
        $assignments = VehicleAssignment::with(['vehicle', 'user']) // 'user' zaten yükleniyor
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('start_time', [$start, $end])
                    ->orWhereBetween('end_time', [$start, $end])
                    ->orWhere(fn($sub) => $sub->where('start_time', '<=', $start)->where('end_time', '>=', $end));
            })->get();

        foreach ($assignments as $assignment) {

            // === GÜNCELLEME: "Sahip" Yetki Kontrolü (Araç Atama) ===
            $canManageThis = $isAdminOrManager || $user->id === $assignment->user_id;

            $extendedProps = [
                'eventType' => 'vehicle_assignment',
                'title' => '🚗 Araç Atama Detayı',
                'id' => $assignment->id,

                // === GÜNCELLEME: Yetkiyi Uygula (Araç Atama) ===
                'editUrl' => $canManageThis ? route('service.assignments.edit', $assignment->id) : null,
                'deleteUrl' => $canManageThis ? route('service.assignments.destroy', $assignment->id) : null,
                // === GÜNCELLEME BİTİŞİ ===

                'details' => [
                    'Araç' => $assignment->vehicle?->plate_number . ' (' . $assignment->vehicle?->type . ')',
                    'Görev' => $assignment->task_description,
                    'Yer' => $assignment->destination,
                    'Talep Eden' => $assignment->requester_name,
                    'Başlangıç' => $assignment->start_time->format('d.m.Y H:i'),
                    'Bitiş' => $assignment->end_time->format('d.m.Y H:i'),
                    'Notlar' => $assignment->notes,
                    'Kayıt Yapan' => $assignment->user?->name,
                ]
            ];

            // === GÜNCELLEME: Eski 'Gate::allows' kontrolü kaldırıldı ===

            $events[] = [
                'title' => 'Araç (' . ($assignment->vehicle->plate_number ?? '?') . '): ' . $assignment->task_description,
                'start' => $assignment->start_time->toIso8601String(),
                'end' => $assignment->end_time->toIso8601String(),
                'color' => '#FBD38D',
                'extendedProps' => $extendedProps
            ];
        }

        return response()->json($events);
    }

    /**
     * Genel Takvim sayfasını gösterir.
     */
    public function showCalendar()
    {
        return view('general-calendar');
    }

    // ===============================================
    // YARDIMCI METODLAR (HomeController'dan kopyalandı)
    // ===============================================

    /**
     * Kargo içeriğini normalize et
     */
    private function normalizeCargoContent($cargo)
    {
        if (empty($cargo)) {
            return 'Bilinmiyor';
        }
        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $normalized = Str::ascii($normalized);
        $specialCases = [
            'LEVBA' => 'LEVHA',
            'LEVBE' => 'LEVHA',
            'PLASTIC' => 'PLASTİK',
            'KAPAK' => 'KAPAK',
            'PLASTİK' => 'PLASTİK',
            'LEVHA' => 'LEVHA',
        ];
        return $specialCases[$normalized] ?? $normalized;
    }

    /**
     * Araç tipini normalize et
     */
    private function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle)) {
            return 'Bilinmiyor';
        }
        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');
        $vehicleMapping = [
            'TIR' => 'TIR',
            'TİR' => 'TIR',
            'TRUCK' => 'TIR',
            'GEMI' => 'GEMI',
            'GEMİ' => 'GEMI',
            'SHIP' => 'GEMI',
            'KAMYON' => 'KAMYON',
            'TRUCK_SMALL' => 'KAMYON',
            'KAMYONET' => 'KAMYON',
        ];
        return $vehicleMapping[$normalized] ?? $normalized;
    }

    /**
     * Etkinlik tiplerini dizi olarak döndürür.
     */
    public function getEventTypes(): array
    {
        // EventController'daki liste ile aynı olmalı
        return [
            'toplanti' => 'Toplantı',
            'egitim' => 'Eğitim',
            'fuar' => 'Fuar',
            'gezi' => 'Gezi',
            'musteri_ziyareti' => 'Müşteri Ziyareti',
            'misafir_karsilama' => 'Misafir Karşılama',
            'diger' => 'Diğer',
        ];
    }
}
