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
use Illuminate\Support\Facades\Log;
use App\Models\Travel;
use Carbon\CarbonPeriod;
use App\Models\MaintenancePlan;

class GeneralCalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([]);
        }

        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        $showLojistik = $request->boolean('lojistik', true);
        $showUretim = $request->boolean('uretim', true);
        $showHizmet = $request->boolean('hizmet', true);
        $showBakim = $request->boolean('bakim', true);
        $importantOnly = $request->boolean('important_only', false);

        $events = [];

        // 1. Sevkiyatlar (Shipments)
        if ($showLojistik && $user->can('view_logistics')) {
            try {
                $activeUnitId = session('active_unit_id');
                $shipments = Shipment::forUser($user) // SCOPE
                    ->with(['onaylayanKullanici', 'user'])
                    ->whereNotNull('tahmini_varis_tarihi')
                    ->whereBetween('tahmini_varis_tarihi', [$start, $end])
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->when($activeUnitId, function ($q) use ($activeUnitId) {
                        return $q->where('business_unit_id', $activeUnitId);
                    })
                    ->get();

                $now = Carbon::now();

                foreach ($shipments as $shipment) {
                    $cikisTarihi = $shipment->cikis_tarihi ? Carbon::parse($shipment->cikis_tarihi) : null;
                    $varisTarihi = $shipment->tahmini_varis_tarihi ? Carbon::parse($shipment->tahmini_varis_tarihi) : null;

                    $color = '#0d6efd';
                    if ($shipment->onaylanma_tarihi) {
                        $color = '#198754';
                    } elseif ($varisTarihi) {
                        if ($now->greaterThan($varisTarihi)) {
                            $color = '#dc3545';
                        } elseif ($varisTarihi->isBetween($now, $now->copy()->addDays(3))) {
                            $color = '#ffc107';
                        }
                    }

                    $onayUrl = ($user->hasRole('admin') || $user->hasRole('roles.lojistik_personeli'))
                        ? route('shipments.onayla', $shipment->id)
                        : null;

                    $extendedProps = [
                        'eventType' => 'shipment',
                        'model_type' => 'shipment',
                        'is_important' => $shipment->is_important,
                        'title' => '🚚 ' . $shipment->kargo_icerigi,
                        'onayUrl' => $onayUrl,
                        'id' => $shipment->id,
                        'details' => [
                            'Araç' => $shipment->arac_tipi,
                            'Plaka' => $shipment->plaka,
                            'Yük' => $shipment->kargo_icerigi
                        ]
                    ];

                    if ($cikisTarihi)
                        $events[] = ['title' => 'ÇIKIŞ: ' . $shipment->kargo_icerigi, 'start' => $cikisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
                    if ($varisTarihi)
                        $events[] = ['title' => 'VARIŞ: ' . $shipment->kargo_icerigi, 'start' => $varisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
                }
            } catch (\Exception $e) {
                Log::error('Takvim Sevkiyat Hatası', ['error' => $e->getMessage()]);
            }
        }

        // 2. Üretim Planları
        if ($showUretim && $user->can('view_production')) {
            try {
                $plans = ProductionPlan::forUser($user) // SCOPE
                    ->with('user')
                    ->whereBetween('week_start_date', [$start, $end])
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($plans as $plan) {
                    $events[] = [
                        'title' => '🏭 ' . $plan->plan_title,
                        'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                        'end' => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
                        'color' => '#4FD1C5',
                        'extendedProps' => [
                            'eventType' => 'production',
                            'model_type' => 'production_plan',
                            'is_important' => $plan->is_important,
                            'id' => $plan->id
                        ]
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Takvim Üretim Hatası', ['error' => $e->getMessage()]);
            }
        }

        // 3. Bakım Planları
        if ($showBakim && $user->can('view_maintenance')) {
            try {
                $plans = MaintenancePlan::forUser($user) // SCOPE
                    ->with(['asset', 'type'])
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('planned_start_date', [$start, $end])
                            ->orWhereBetween('planned_end_date', [$start, $end]);
                    })
                    ->when($importantOnly, fn($q) => $q->whereIn('priority', ['high', 'critical']))
                    ->get();

                foreach ($plans as $plan) {
                    $color = match ($plan->status) {
                        'pending' => '#F6E05E', 'in_progress' => '#3182CE', 'completed' => '#48BB78', 'cancelled' => '#E53E3E', default => '#A0AEC0'
                    };
                    $events[] = [
                        'title' => '🔧 ' . ($plan->asset->name ?? '?'),
                        'start' => $plan->planned_start_date->format('Y-m-d\TH:i:s'),
                        'end' => $plan->planned_end_date->format('Y-m-d\TH:i:s'),
                        'color' => $color,
                        'extendedProps' => [
                            'eventType' => 'maintenance',
                            'model_type' => 'maintenance_plan',
                            'id' => $plan->id
                        ]
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Takvim Bakım Hatası', ['error' => $e->getMessage()]);
            }
        }

        // 4. Etkinlikler & İdari İşler
        if ($showHizmet && $user->can('view_administrative')) {
            // Etkinlikler
            try {
                $serviceEvents = Event::forUser($user) // SCOPE
                    ->whereBetween('start_datetime', [$start, $end])
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($serviceEvents as $event) {
                    $events[] = [
                        'title' => '📅 ' . $event->title,
                        'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                        'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                        'color' => '#F093FB',
                        'extendedProps' => ['eventType' => 'service_event', 'model_type' => 'event', 'id' => $event->id]
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Takvim Etkinlik Hatası', ['error' => $e->getMessage()]);
            }

            // Araç Atamaları
            try {
                $assignments = VehicleAssignment::forUser($user) // SCOPE
                    ->with('vehicle')
                    ->whereBetween('start_time', [$start, $end])
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($assignments as $assignment) {
                    $events[] = [
                        'title' => '🚗 ' . ($assignment->vehicle?->plate_number ?? '?'),
                        'start' => $assignment->start_time->format('Y-m-d\TH:i:s'),
                        'end' => $assignment->end_time->format('Y-m-d\TH:i:s'),
                        'color' => '#FBD38D',
                        'extendedProps' => ['eventType' => 'vehicle_assignment', 'model_type' => 'vehicle_assignment', 'id' => $assignment->id]
                    ];
                }
            } catch (\Exception $e) {
                Log::error('Takvim Araç Hatası', ['error' => $e->getMessage()]);
            }

            // Seyahatler
            try {
                $travels = Travel::forUser($user) // SCOPE
                    ->whereDate('start_date', '<=', $end)
                    ->whereDate('end_date', '>=', $start)
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($travels as $travel) {
                    $period = CarbonPeriod::create($travel->start_date, $travel->end_date);
                    foreach ($period as $date) {
                        if ($date->between($start, $end)) {
                            $events[] = [
                                'title' => '✈️ ' . $travel->name,
                                'start' => $date->toDateString(),
                                'allDay' => true,
                                'color' => '#A78BFA',
                                'extendedProps' => ['eventType' => 'travel', 'model_type' => 'travel', 'id' => $travel->id]
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Seyahat Hatası', ['error' => $e->getMessage()]);
            }
        }

        return response()->json($events);
    }

    public function showCalendar()
    {
        return view('general-calendar');
    }

    // --- YARDIMCI METODLAR ---

    private function normalizeCargoContent($cargo)
    {
        if (empty($cargo)) {
            return 'Bilinmiyor';
        }

        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $specialCases = [
            'LEVBA' => 'LEVHA',
            'LEVBE' => 'LEVHA',
            'PLASTIC' => 'PLASTİK',
            'PLASTIK' => 'PLASTİK',
            'PREFORM' => 'PREFORM',
            'COPED' => 'COPED'
        ];

        return $specialCases[$normalized] ?? $normalized;
    }

    private function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle)) {
            return 'Bilinmiyor';
        }

        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');

        $mapping = [
            'TIR' => 'TIR',
            'TRUCK' => 'TIR',
            'GEMI' => 'GEMİ',
            'SHIP' => 'GEMİ',
            'KAMYON' => 'KAMYON',
            'PICKUP' => 'KAMYONET',
            'KAMYONET' => 'KAMYONET'
        ];

        return $mapping[$normalized] ?? $normalized;
    }

    public function getEventTypes(): array
    {
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

    public function toggleImportant(Request $request)
    {
        $user = Auth::user();

        // 1. GÜVENLİK: Spatie Rol Kontrolü (Eski in_array kaldırıldı)
        // Admin, Yönetici veya Müdür yetkisi olanlar yapabilsin
        if (!$user || !$user->hasRole(['admin', 'yonetici', 'mudur'])) {
            return response()->json(['success' => false, 'message' => 'Bu işlem için yetkiniz yok.'], 403);
        }

        // 2. VALIDATION
        $validated = $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'is_important' => 'required|boolean', // true/false/0/1/ "true" hepsini kabul eder
        ]);

        $modelId = $validated['model_id'];
        // Laravel helper ile boolean çevrimi
        $isImportant = $request->boolean('is_important');

        try {
            // Hangi Model?
            $modelClass = match ($validated['model_type']) {
                'shipment' => \App\Models\Shipment::class,
                'production_plan' => \App\Models\ProductionPlan::class,
                'event' => \App\Models\Event::class,
                'vehicle_assignment' => \App\Models\VehicleAssignment::class,
                'travel' => \App\Models\Travel::class,
                'maintenance_plan' => \App\Models\MaintenancePlan::class,
                default => null,
            };

            if (!$modelClass) {
                return response()->json(['success' => false, 'message' => 'Geçersiz veri türü.'], 400);
            }

            // 3. VERİ GÜVENLİĞİ (BUSINESS UNIT CHECK) 🔒
            // forUser($user) ekleyerek, kullanıcının sadece kendi fabrikasındaki veriyi
            // bulabilmesini sağlıyoruz. Başkasının ID'sini gönderirse null döner.
            $record = $modelClass::forUser($user)->find($modelId);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı veya yetkiniz yok.'], 404);
            }

            // 4. GÜNCELLEME İŞLEMİ
            if ($validated['model_type'] === 'maintenance_plan') {
                // Bakım planı için priority sütununu kullanıyoruz
                $record->priority = $isImportant ? 'critical' : 'normal';
            } else {
                // Diğerleri için is_important sütunu
                $record->is_important = $isImportant;
            }

            $record->save();

            return response()->json([
                'success' => true,
                'message' => 'Durum güncellendi.',
                'new_state' => $isImportant
            ]);

        } catch (\Exception $e) {
            Log::error('ToggleImportant Hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Sunucu hatası oluştu.'], 500);
        }
    }
}