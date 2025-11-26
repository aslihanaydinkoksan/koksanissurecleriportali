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

        $isAdminOrManager = in_array($user->role, ['admin', 'yönetici']);
        $start = Carbon::parse($request->input('start'))->startOfDay();
        $end = Carbon::parse($request->input('end'))->endOfDay();

        $showLojistik = $request->boolean('lojistik', true);
        $showUretim = $request->boolean('uretim', true);
        $showHizmet = $request->boolean('hizmet', true);
        $showBakim = $request->boolean('bakim', true);
        $importantOnly = $request->boolean('important_only', false);

        $events = [];

        // 1. Sevkiyatlar (Shipments)
        if ($showLojistik) {
            try {
                $shipments = Shipment::with(['onaylayanKullanici', 'user'])
                    ->whereNotNull('tahmini_varis_tarihi')
                    ->whereBetween('tahmini_varis_tarihi', [$start, $end])
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                $now = Carbon::now();

                foreach ($shipments as $shipment) {
                    try {
                        $cikisTarihi = $shipment->cikis_tarihi ? Carbon::parse($shipment->cikis_tarihi) : null;
                        $varisTarihi = $shipment->tahmini_varis_tarihi ? Carbon::parse($shipment->tahmini_varis_tarihi) : null;

                        $normalizedKargo = $this->normalizeCargoContent($shipment->kargo_icerigi);
                        $normalizedAracTipi = $this->normalizeVehicleType($shipment->arac_tipi);

                        // Eski statik kontrol (Geri uyumluluk için editUrl içinde tutuyoruz)
                        $canManageThis = $isAdminOrManager || $user->id === $shipment->user_id;

                        // Renk belirleme
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

                        $extendedProps = [
                            'eventType' => 'shipment',
                            'model_type' => 'shipment',
                            'is_important' => $shipment->is_important,
                            'title' => '🚚 Sevkiyat Detayı: ' . $normalizedKargo,
                            'id' => $shipment->id,
                            'user_id' => $shipment->user_id,
                            // YENİ EKLENDİ: Departman ID
                            'department_id' => $shipment->user->department_id ?? null,
                            'editUrl' => route('shipments.edit', $shipment->id),
                            'deleteUrl' => route('shipments.destroy', $shipment->id),
                            'exportUrl' => route('shipments.export', $shipment->id),
                            'onayUrl' => route('shipments.onayla', $shipment->id),
                            'onayKaldirUrl' => route('shipments.onayiGeriAl', $shipment->id),
                            'details' => [
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
                                'Dosya Yolu' => $shipment->dosya_yolu ? asset('storage/' . $shipment->dosya_yolu) : null,
                                'Onay Durumu' => $shipment->onaylanma_tarihi ? $shipment->onaylanma_tarihi->format('d.m.Y H:i') : null,
                                'Onaylayan' => $shipment->onaylayanKullanici?->name,
                            ]
                        ];

                        $events[] = [
                            'title' => 'VARIŞ: ' . $normalizedKargo . ' (' . $normalizedAracTipi . ')',
                            'start' => $shipment->tahmini_varis_tarihi->toIso8601String(),
                            'color' => $color,
                            'extendedProps' => $extendedProps
                        ];
                    } catch (\Exception $e) {
                        Log::error('Takvim Sevkiyat Hatası ID: ' . $shipment->id, ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Sevkiyat Sorgusu Başarısız', ['error' => $e->getMessage()]);
            }
        }

        // 2. Üretim Planları (Production Plans)
        if ($showUretim) {
            try {
                $plans = ProductionPlan::with('user')
                    ->whereBetween('week_start_date', [$start, $end])
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($plans as $plan) {
                    try {
                        $events[] = [
                            'title' => 'Üretim: ' . $plan->plan_title,
                            'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                            'end' => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
                            'color' => '#4FD1C5',
                            'extendedProps' => [
                                'eventType' => 'production',
                                'model_type' => 'production_plan',
                                'is_important' => $plan->is_important,
                                'title' => '📅 Üretim Planı Detayı',
                                'id' => $plan->id,
                                'user_id' => $plan->user_id,
                                // YENİ EKLENDİ: Departman ID
                                'department_id' => $plan->user->department_id ?? null,
                                'editUrl' => route('production.plans.edit', $plan->id),
                                'deleteUrl' => route('production.plans.destroy', $plan->id),
                                'details' => [
                                    'Plan Başlığı' => $plan->plan_title,
                                    'Hafta Başlangıcı' => $plan->week_start_date->format('d.m.Y'),
                                    'Plan Detayları' => $plan->plan_details, // JS tarafında özel tablo olarak işleniyor
                                    'Oluşturan' => $plan->user?->name,
                                    'Kayıt Tarihi' => $plan->created_at->format('d.m.Y H:i'),
                                ]
                            ]
                        ];
                    } catch (\Exception $e) {
                        Log::error('Takvim Üretim Planı Hatası ID: ' . $plan->id, ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Üretim Sorgusu Başarısız', ['error' => $e->getMessage()]);
            }
        }

        // 3. Etkinlikler (Events)
        if ($showHizmet) {
            try {
                $serviceEvents = Event::with('user')
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('start_datetime', [$start, $end])
                            ->orWhereBetween('end_datetime', [$start, $end])
                            ->orWhere(fn($sub) => $sub->where('start_datetime', '<=', $start)->where('end_datetime', '>=', $end));
                    })
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($serviceEvents as $event) {
                    try {
                        $databaseEventType = $event->event_type ?? 'diger';
                        $finalEventType = ($databaseEventType === 'diger') ? 'general' : 'service_event';

                        $events[] = [
                            'title' => 'Etkinlik: ' . $event->title,
                            'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                            'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                            'color' => '#F093FB',
                            'extendedProps' => [
                                'eventType' => $finalEventType,
                                'model_type' => 'event',
                                'is_important' => $event->is_important,
                                'title' => '🎉 Etkinlik Detayı: ' . $event->title,
                                'id' => $event->id,
                                'user_id' => $event->user_id,
                                // YENİ EKLENDİ: Departman ID
                                'department_id' => $event->user->department_id ?? null,
                                'editUrl' => route('service.events.edit', $event->id),
                                'deleteUrl' => route('service.events.destroy', $event->id),
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
                    } catch (\Exception $e) {
                        Log::error('Takvim Etkinlik Hatası ID: ' . $event->id, ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Etkinlik Sorgusu Başarısız', ['error' => $e->getMessage()]);
            }
        }

        // 4. Araç Atamaları (Vehicle Assignments)
        if ($showHizmet) {
            try {
                $assignments = VehicleAssignment::with(['vehicle', 'createdBy'])
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('start_time', [$start, $end])
                            ->orWhereBetween('end_time', [$start, $end])
                            ->orWhere(fn($sub) => $sub->where('start_time', '<=', $start)->where('end_time', '>=', $end));
                    })
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($assignments as $assignment) {
                    try {
                        $extendedProps = [
                            'eventType' => 'vehicle_assignment',
                            'model_type' => 'vehicle_assignment',
                            'is_important' => $assignment->is_important,
                            'title' => '🚗 Araç Atama Detayı',
                            'id' => $assignment->id,
                            // Atamayı yapan kişinin ID'si (Assignment modelinde user_id yoksa created_by kullanıyoruz)
                            'user_id' => $assignment->created_by_user_id ?? $assignment->user_id,
                            // YENİ EKLENDİ: Departman ID (Oluşturan kişinin departmanı)
                            'department_id' => $assignment->createdBy->department_id ?? null,
                            'editUrl' => route('service.assignments.edit', $assignment->id),
                            'deleteUrl' => route('service.assignments.destroy', $assignment->id),
                            'details' => [
                                'Araç' => $assignment->vehicle?->plate_number . ' (' . $assignment->vehicle?->type . ')',
                                'Görev' => $assignment->task_description,
                                'Yer' => $assignment->destination,
                                'Talep Eden' => $assignment->requester_name,
                                'Başlangıç' => $assignment->start_time->format('d.m.Y H:i'),
                                'Bitiş' => $assignment->end_time->format('d.m.Y H:i'),
                                'Notlar' => $assignment->notes,
                                'Kayıt Yapan' => $assignment->createdBy?->name,
                            ]
                        ];

                        $events[] = [
                            'title' => '🚗 Araç (' . ($assignment->vehicle?->plate_number ?? '?') . '): ' . $assignment->task_description,
                            'start' => $assignment->start_time->format('Y-m-d\TH:i:s'),
                            'end' => $assignment->end_time->format('Y-m-d\TH:i:s'),
                            'color' => '#FBD38D',
                            'extendedProps' => $extendedProps
                        ];
                    } catch (\Exception $e) {
                        Log::error('Takvim Araç Atama Hatası ID: ' . $assignment->id, ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Araç Atama Sorgusu Başarısız', ['error' => $e->getMessage()]);
            }
        }

        // 5. Seyahat Planları (Travels)
        if ($showHizmet) { // Seyahat genelde İdari İşler/Hizmet altında görünür
            try {
                $travels = Travel::with('user')
                    ->where(function ($q) use ($start, $end) {
                        $q->whereDate('start_date', '<=', $end)
                            ->whereDate('end_date', '>=', $start);
                    })
                    ->when($importantOnly, fn($q) => $q->where('is_important', true))
                    ->get();

                foreach ($travels as $travel) {
                    try {
                        $period = CarbonPeriod::create($travel->start_date, $travel->end_date);
                        foreach ($period as $date) {
                            $events[] = [
                                'title' => '✈️ Seyahat: ' . $travel->name,
                                'start' => $date->toDateString(),
                                'allDay' => true,
                                'color' => '#A78BFA',
                                'extendedProps' => [
                                    'eventType' => 'travel',
                                    'model_type' => 'travel',
                                    'is_important' => $travel->is_important,
                                    'title' => '✈️ Seyahat Detayı: ' . $travel->name,
                                    'id' => $travel->id,
                                    'user_id' => $travel->user_id,
                                    // YENİ EKLENDİ: Departman ID
                                    'department_id' => $travel->user->department_id ?? null,
                                    'url' => route('travels.show', $travel),
                                    'editUrl' => route('travels.edit', $travel),
                                    'deleteUrl' => route('travels.destroy', $travel),
                                    'details' => [
                                        'Plan Adı' => $travel->name,
                                        'Oluşturan' => $travel->user?->name,
                                        'Başlangıç' => $travel->start_date->format('d.m.Y'),
                                        'Bitiş' => $travel->end_date->format('d.m.Y'),
                                        'Durum' => $travel->status == 'planned' ? 'Planlandı' : 'Tamamlandı',
                                    ]
                                ]
                            ];
                        }
                    } catch (\Exception $e) {
                        Log::error('Takvim Seyahat Hatası ID: ' . $travel->id, ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Seyahat Sorgusu Başarısız', ['error' => $e->getMessage()]);
            }
        }

        // 6. Bakım Planları (Maintenance Plans)
        if ($showBakim) {
            try {
                $maintenancePlans = MaintenancePlan::with(['asset', 'type', 'user'])
                    ->where(function ($q) use ($start, $end) {
                        $q->whereBetween('planned_start_date', [$start, $end])
                            ->orWhereBetween('planned_end_date', [$start, $end])
                            ->orWhere(fn($sub) => $sub->where('planned_start_date', '<=', $start)->where('planned_end_date', '>=', $end));
                    })
                    ->when($importantOnly, fn($q) => $q->whereIn('priority', ['high', 'critical']))
                    ->get();

                foreach ($maintenancePlans as $plan) {
                    try {
                        $color = match ($plan->status) {
                            'pending' => '#F6E05E',
                            'in_progress' => '#3182CE',
                            'completed' => '#48BB78',
                            'cancelled' => '#E53E3E',
                            default => '#A0AEC0',
                        };

                        $events[] = [
                            'title' => 'Bakım: ' . $plan->asset->name,
                            'start' => $plan->planned_start_date->format('Y-m-d\TH:i:s'),
                            'end' => $plan->planned_end_date->format('Y-m-d\TH:i:s'),
                            'color' => $color,
                            'extendedProps' => [
                                'eventType' => 'maintenance',
                                'model_type' => 'maintenance_plan',
                                'is_important' => ($plan->priority == 'critical' || $plan->priority == 'high'),
                                'title' => '🔧 Bakım Planı: ' . $plan->title,
                                'id' => $plan->id,
                                'user_id' => $plan->user_id,
                                // YENİ EKLENDİ: Departman ID
                                'department_id' => $plan->user->department_id ?? null,
                                'url' => route('maintenance.show', $plan->id),
                                'editUrl' => route('maintenance.edit', $plan->id),
                                'deleteUrl' => route('maintenance.destroy', $plan->id),
                                'details' => [
                                    'Başlık' => $plan->title,
                                    'Varlık' => $plan->asset->name . ' (' . ($plan->asset->code ?? '-') . ')',
                                    'Tür' => $plan->type->name,
                                    'Öncelik' => ucfirst($plan->priority),
                                    'Durum' => $plan->status_label,
                                    'Başlangıç' => $plan->planned_start_date->format('d.m.Y H:i'),
                                    'Bitiş' => $plan->planned_end_date->format('d.m.Y H:i'),
                                    'Sorumlu' => $plan->user->name,
                                    'Açıklama' => $plan->description,
                                ]
                            ]
                        ];
                    } catch (\Exception $e) {
                        Log::error('Takvim Bakım Planı Hatası ID: ' . $plan->id, ['error' => $e->getMessage()]);
                    }
                }
            } catch (\Exception $e) {
                Log::error('Takvim Bakım Planı Sorgusu Başarısız', ['error' => $e->getMessage()]);
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
        if (empty($cargo))
            return 'Bilinmiyor';
        $normalized = mb_strtoupper(trim($cargo), 'UTF-8');
        $normalized = Str::ascii($normalized);
        $specialCases = ['LEVBA' => 'LEVHA', 'LEVBE' => 'LEVHA', 'PLASTIC' => 'PLASTİK'];
        return $specialCases[$normalized] ?? $normalized;
    }

    private function normalizeVehicleType($vehicle)
    {
        if (empty($vehicle))
            return 'Bilinmiyor';
        $normalized = mb_strtoupper(trim($vehicle), 'UTF-8');
        $mapping = ['TIR' => 'TIR', 'TRUCK' => 'TIR', 'GEMI' => 'GEMI', 'SHIP' => 'GEMI'];
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
        if (!$user || !in_array($user->role, ['admin', 'yönetici'])) {
            return response()->json(['success' => false, 'message' => 'Yetkiniz yok.'], 403);
        }

        $validated = $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'is_important' => 'required|boolean',
        ]);

        $modelId = $validated['model_id'];
        $isImportant = filter_var($validated['is_important'], FILTER_VALIDATE_BOOLEAN);

        try {
            if ($validated['model_type'] === 'maintenance_plan') {
                $record = \App\Models\MaintenancePlan::withTrashed()->find($modelId);
                if (!$record)
                    return response()->json(['success' => false, 'message' => 'Bulunamadı'], 404);

                $record->priority = $isImportant ? 'critical' : 'normal';
                $record->save();

                return response()->json(['success' => true, 'message' => 'Bakım önceliği güncellendi.']);
            }

            // Diğer Modeller
            $modelClass = match ($validated['model_type']) {
                'shipment' => \App\Models\Shipment::class,
                'production_plan' => \App\Models\ProductionPlan::class,
                'event' => \App\Models\Event::class,
                'vehicle_assignment' => \App\Models\VehicleAssignment::class,
                'travel' => \App\Models\Travel::class,
                default => null,
            };

            if (!$modelClass)
                return response()->json(['success' => false], 400);

            $model = $modelClass::find($modelId);
            if (!$model)
                return response()->json(['success' => false], 404);

            $model->update(['is_important' => $isImportant]);
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            Log::error('toggleImportant hatası', ['error' => $e->getMessage()]);
            return response()->json(['success' => false, 'message' => 'Hata oluştu.'], 500);
        }
    }
}