<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
// Modeller
use App\Models\Shipment;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\Travel;
use App\Models\MaintenancePlan;
use App\Models\Todo;

class GeneralCalendarController extends Controller
{
    public function getEvents(Request $request)
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json([]);
        }

        // FullCalendar'dan gelen tarih aralığı
        // Eğer start/end gelmezse varsayılan olarak bu ayı al
        $start = $request->has('start') ? Carbon::parse($request->input('start'))->startOfDay() : Carbon::now()->startOfMonth();
        $end = $request->has('end') ? Carbon::parse($request->input('end'))->endOfDay() : Carbon::now()->endOfMonth();

        // Filtreler
        $showLojistik = $request->boolean('lojistik', true);
        $showUretim = $request->boolean('uretim', true);
        $showHizmet = $request->boolean('hizmet', true);
        $showBakim = $request->boolean('bakim', true);
        $importantOnly = $request->boolean('important_only', false);

        $events = [];

        // 1. Lojistik Verileri
        if ($showLojistik && $user->can('view_logistics')) {
            $data = $this->getLojistikData($user, $start, $end, $importantOnly);
            $events = array_merge($events, $data);
        }

        // 2. Üretim Verileri
        if ($showUretim && $user->can('view_production')) {
            $data = $this->getUretimData($user, $start, $end, $importantOnly);
            $events = array_merge($events, $data);
        }

        // 3. Bakım Verileri
        if ($showBakim && $user->can('view_maintenance')) {
            $data = $this->getBakimData($user, $start, $end, $importantOnly);
            $events = array_merge($events, $data);
        }

        // 4. İdari İşler / Hizmet Verileri (Etkinlik, Araç, Seyahat, Ziyaret)
        if ($showHizmet && $user->can('view_administrative')) {
            $data = $this->getHizmetData($user, $start, $end, $importantOnly);
            $events = array_merge($events, $data);

            // Yeni: Müşteri Ziyaretleri (CRM)
            $visitData = $this->getCustomerVisitData($user, $start, $end);
            $events = array_merge($events, $visitData);
        }

        // 5. Todo'lar (Eğer Hizmet filtresi açıksa veya kullanıcı istiyorsa gösterelim)
        // Todo kişisel olduğu için her zaman gösterilebilir veya hizmet'e bağlanabilir.
        if ($showHizmet) {
            $data = $this->getTodoData($user, $start, $end, $importantOnly);
            $events = array_merge($events, $data);
        }

        // Çakışan ID'leri temizle (Frontend hatasını önlemek için)
        // $uniqueEvents = collect($events)->unique(function ($item) {
        //     return $item['extendedProps']['model_type'] . '-' . $item['id'];
        // })->values()->all();

        return response()->json($events);
    }

    public function showCalendar()
    {
        return view('general-calendar');
    }

    // --- YARDIMCI METODLAR (VERİ TOPLAYICILAR) ---

    private function getLojistikData($user, $start, $end, $importantOnly)
    {
        $events = [];
        $now = Carbon::now();

        // DÜZELTME: forUser kaldırıldı. Trait (Global Scope) otomatik filtreliyor.
        $shipments = Shipment::with(['onaylayanKullanici', 'user'])
            ->whereNotNull('tahmini_varis_tarihi')
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('tahmini_varis_tarihi', [$start, $end])
                    ->orWhereBetween('cikis_tarihi', [$start, $end]);
            })
            ->when($importantOnly, fn($q) => $q->where('is_important', true))
            ->get();

        foreach ($shipments as $shipment) {
            $cikisTarihi = $shipment->cikis_tarihi ? Carbon::parse($shipment->cikis_tarihi) : null;
            $varisTarihi = $shipment->tahmini_varis_tarihi ? Carbon::parse($shipment->tahmini_varis_tarihi) : null;

            $color = '#0d6efd';
            if ($shipment->onaylanma_tarihi)
                $color = '#198754';
            elseif ($varisTarihi) {
                if ($now->greaterThan($varisTarihi))
                    $color = '#dc3545';
                elseif ($varisTarihi->isBetween($now, $now->copy()->addDays(3)))
                    $color = '#ffc107';
            }

            $onayUrl = ($user->hasRole('admin') || $user->hasRole('roles.lojistik_personeli')) ? route('shipments.onayla', $shipment->id) : null;

            $detaylar = [
                'Yük Tipi' => $shipment->shipment_type ?? 'Genel',
                'Araç Tipi' => $shipment->arac_tipi ?? 'Belirtilmedi',
                'Kargo İçeriği' => $shipment->kargo_icerigi,
                'Miktar' => ($shipment->kargo_miktari ?? '-') . ' ' . ($shipment->kargo_tipi ?? ''),
            ];

            // Detayları doldurma mantığı...
            $aracTipiLower = mb_strtolower($shipment->arac_tipi ?? '');
            if (str_contains($aracTipiLower, 'gemi') || str_contains(mb_strtolower($shipment->shipment_type ?? ''), 'deniz')) {
                $detaylar['Gemi Adı'] = $shipment->gemi_adi ?? '-';
                $detaylar['IMO Numarası'] = $shipment->imo_numarasi ?? '-';
                $detaylar['Kalkış Limanı'] = $shipment->kalkis_limani ?? '-';
                $detaylar['Varış Limanı'] = $shipment->varis_limani ?? '-';
            } else {
                $detaylar['Plaka'] = $shipment->plaka ?? '-';
                if (!empty($shipment->dorce_plakasi))
                    $detaylar['Dorse Plaka'] = $shipment->dorce_plakasi;
                $detaylar['Sürücü'] = $shipment->sofor_adi ?? '-';
                $detaylar['Kalkış Noktası'] = $shipment->kalkis_noktasi ?? '-';
                $detaylar['Varış Noktası'] = $shipment->varis_noktasi ?? '-';
                if (!empty($shipment->nakliye_firmasi))
                    $detaylar['Nakliye Firması'] = $shipment->nakliye_firmasi;
            }

            $detaylar['Çıkış Tarihi'] = $cikisTarihi ? $cikisTarihi->format('d.m.Y H:i') : '-';
            $detaylar['Tahmini Varış'] = $varisTarihi ? $varisTarihi->format('d.m.Y H:i') : '-';
            $detaylar['Onay Durumu'] = $shipment->onaylanma_tarihi ? $shipment->onaylanma_tarihi : null;
            $detaylar['Onaylayan'] = $shipment->onaylayanKullanici->name ?? null;
            $detaylar['Açıklama'] = $shipment->aciklamalar ?? null;

            $extendedProps = [
                'eventType' => 'shipment',
                'model_type' => 'shipment',
                'id' => $shipment->id,
                'is_important' => $shipment->is_important,
                'title' => '🚚 ' . $shipment->kargo_icerigi,
                'onayUrl' => $onayUrl,
                'details' => $detaylar
            ];

            if ($cikisTarihi && $cikisTarihi->between($start, $end))
                $events[] = ['title' => 'ÇIKIŞ: ' . $shipment->kargo_icerigi, 'start' => $cikisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];

            if ($varisTarihi && $varisTarihi->between($start, $end))
                $events[] = ['title' => 'VARIŞ: ' . $shipment->kargo_icerigi, 'start' => $varisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
        }
        return $events;
    }

    private function getUretimData($user, $start, $end, $importantOnly)
    {
        $events = [];
        // DÜZELTME: forUser kaldırıldı
        $plans = ProductionPlan::with('user')
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
                    'id' => $plan->id,
                    'title' => $plan->plan_title,
                    'details' => [
                        'Plan Adı' => $plan->plan_title,
                        'Başlangıç Tarihi' => $plan->week_start_date->format('d.m.Y'),
                        'Oluşturulma' => $plan->created_at->format('d.m.Y H:i'),
                        'Oluşturan' => $plan->user ? $plan->user->name : '-',
                        'Plan Detayları' => $plan->plan_details
                    ]
                ]
            ];
        }
        return $events;
    }

    private function getBakimData($user, $start, $end, $importantOnly)
    {
        $events = [];
        // DÜZELTME: forUser kaldırıldı
        $plans = MaintenancePlan::with(['asset', 'type', 'user'])
            ->where(function ($q) use ($start, $end) {
                $q->whereBetween('planned_start_date', [$start, $end])
                    ->orWhereBetween('planned_end_date', [$start, $end]);
            })
            ->when($importantOnly, fn($q) => $q->whereIn('priority', ['high', 'critical']))
            ->get();

        foreach ($plans as $plan) {
            $color = match ($plan->status) {
                'pending' => '#F6E05E', 'in_progress' => '#3182CE', 'completed' => '#48BB78', 'cancelled' => '#E53E3E', default => '#A0AEC0',
            };

            $baslik = '🔧 ' . ($plan->asset->name ?? 'Varlık Silinmiş');
            if (!empty($plan->title))
                $baslik .= ' - ' . $plan->title;

            $detaylar = [
                'Başlık' => $plan->title ?? '-',
                'Varlık' => $plan->asset->name ?? 'Bilinmiyor',
                'Bakım Türü' => $plan->type->name ?? 'Genel',
                'Sorumlu' => $plan->user->name ?? '-',
                'Öncelik' => ucfirst($plan->priority ?? 'Normal'),
                'Durum' => ucfirst($plan->status ?? 'Pending'),
                'Planlanan Başlangıç' => $plan->planned_start_date ? $plan->planned_start_date->format('d.m.Y H:i') : '-',
                'Planlanan Bitiş' => $plan->planned_end_date ? $plan->planned_end_date->format('d.m.Y H:i') : '-',
            ];

            if ($plan->actual_start_date)
                $detaylar['Gerçekleşen Başlangıç'] = Carbon::parse($plan->actual_start_date)->format('d.m.Y H:i');
            if ($plan->actual_end_date)
                $detaylar['Gerçekleşen Bitiş'] = Carbon::parse($plan->actual_end_date)->format('d.m.Y H:i');
            if (!empty($plan->completion_note))
                $detaylar['Sonuç Notu'] = $plan->completion_note;
            $detaylar['Açıklama'] = $plan->description ?? null;

            $events[] = [
                'title' => $baslik,
                'start' => $plan->planned_start_date->format('Y-m-d\TH:i:s'),
                'end' => $plan->planned_end_date->format('Y-m-d\TH:i:s'),
                'color' => $color,
                'extendedProps' => [
                    'eventType' => 'maintenance',
                    'model_type' => 'maintenance_plan',
                    'is_important' => ($plan->priority == 'critical' || $plan->priority == 'high'),
                    'id' => $plan->id,
                    'details' => $detaylar
                ]
            ];
        }
        return $events;
    }

    private function getHizmetData($user, $start, $end, $importantOnly)
    {
        $events = [];

        // 1. Etkinlikler
        try {
            // DÜZELTME: forUser kaldırıldı
            $serviceEvents = Event::with(['user', 'customer'])
                ->whereBetween('start_datetime', [$start, $end])
                ->when($importantOnly, fn($q) => $q->where('is_important', true))
                ->get();

            foreach ($serviceEvents as $event) {
                $detaylar = [
                    'Etkinlik Başlığı' => $event->title,
                    'Tür' => $event->type_label ?? 'Genel',
                    'Konum' => $event->location ?? '-',
                    'Başlangıç' => $event->start_datetime->format('d.m.Y H:i'),
                    'Bitiş' => $event->end_datetime->format('d.m.Y H:i'),
                ];

                if ($event->customer_id)
                    $detaylar['Müşteri'] = $event->customer->name ?? ('Müşteri #' . $event->customer_id);
                if (!empty($event->visit_purpose))
                    $detaylar['Ziyaret Amacı'] = $event->visit_purpose;

                $status = $event->visit_status ?? 'planlandi';
                $detaylar['Durum'] = ucfirst($status);

                if ((strtolower($status) === 'iptal' || strtolower($status) === 'cancelled') && !empty($event->cancellation_reason)) {
                    $detaylar['İptal Nedeni'] = $event->cancellation_reason;
                }
                if (!empty($event->after_sales_notes))
                    $detaylar['Satış Sonrası Notlar'] = Str::limit($event->after_sales_notes, 50);
                $detaylar['Açıklama'] = $event->description ?? null;

                $events[] = [
                    'title' => '📅 ' . $event->title ?? $event->type_label,
                    'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                    'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                    'className' => 'bg-' . $event->color_class,
                    'extendedProps' => [
                        'eventType' => $event->event_type,
                        'model_type' => 'event',
                        'type_label'   => $event->type_label,
                        'is_important' => $event->is_important,
                        'id' => $event->id,
                        'details' => $detaylar
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Takvim Etkinlik Hatası', ['error' => $e->getMessage()]);
        }

        // 2. Araç Atamaları
        try {
            // DÜZELTME: forUser kaldırıldı
            $assignments = VehicleAssignment::with(['vehicle', 'createdBy', 'driver'])
                ->whereBetween('start_time', [$start, $end])
                ->when($importantOnly, fn($q) => $q->where('is_important', true))
                ->get();

            foreach ($assignments as $assignment) {
                $aracBilgisi = $assignment->vehicle ? ($assignment->vehicle->plate_number . ' - ' . $assignment->vehicle->brand) : 'Araç Bilgisi Yok';

                $events[] = [
                    'title' => '🚗 ' . ($assignment->vehicle?->plate_number ?? '?'),
                    'start' => $assignment->start_time->format('Y-m-d\TH:i:s'),
                    'end' => $assignment->end_time->format('Y-m-d\TH:i:s'),
                    'color' => '#FBD38D',
                    'extendedProps' => [
                        'eventType' => 'vehicle_assignment',
                        'model_type' => 'vehicle_assignment',
                        'is_important' => $assignment->is_important,
                        'id' => $assignment->id,
                        'details' => [
                            'Araç' => $aracBilgisi,
                            'Görev Tanımı' => $assignment->task_description,
                            'Talep Eden' => $assignment->createdBy?->name ?? '-',
                            'Sürücü' => $assignment->driver?->name ?? '-',
                            'Başlangıç' => $assignment->start_time->format('d.m.Y H:i'),
                            'Bitiş' => $assignment->end_time->format('d.m.Y H:i'),
                            'Durum' => ucfirst($assignment->status)
                        ]
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Takvim Araç Hatası', ['error' => $e->getMessage()]);
        }

        // 3. Seyahatler
        try {
            // DÜZELTME: forUser kaldırıldı
            $travels = Travel::whereDate('start_date', '<=', $end)
                ->whereDate('end_date', '>=', $start)
                ->when($importantOnly, fn($q) => $q->where('is_important', true))
                ->get();

            foreach ($travels as $travel) {
                $events[] = [
                    'title' => '✈️ ' . $travel->name,
                    'start' => $travel->start_date->format('Y-m-d'),
                    'end' => $travel->end_date->addDay()->format('Y-m-d'), // FullCalendar için +1 gün
                    'allDay' => true,
                    'color' => '#A78BFA',
                    'extendedProps' => [
                        'eventType' => 'travel',
                        'model_type' => 'travel',
                        'is_important' => $travel->is_important,
                        'id' => $travel->id,
                        'details' => [
                            'Seyahat Adı' => $travel->name,
                            'Başlangıç' => $travel->start_date->format('d.m.Y'),
                            'Bitiş' => $travel->end_date->format('d.m.Y'),
                            'Durum' => $travel->status ?? 'Planlandı',
                            'Açıklama' => $travel->description ?? null
                        ]
                    ]
                ];
            }
        } catch (\Exception $e) {
            Log::error('Takvim Seyahat Hatası', ['error' => $e->getMessage()]);
        }

        return $events;
    }

    private function getTodoData($user, $start, $end, $importantOnly)
    {
        // DÜZELTME: Todo'da Trait yoksa standart where, varsa Trait zaten çalışır.
        // Ama garanti olsun diye where('user_id') ekliyoruz.
        $todos = Todo::where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->whereBetween('due_date', [$start, $end])
            ->where('is_completed', false)
            ->when($importantOnly, fn($q) => $q->where('priority', 'high'))
            ->get();

        $events = [];
        foreach ($todos as $todo) {
            $color = match ($todo->priority) {
                'high' => '#dc3545',
                'medium' => '#fd7e14',
                'low' => '#20c997',
                default => '#6c757d'
            };

            $oncelikText = match ($todo->priority) {
                'high' => 'Yüksek', 'medium' => 'Orta', 'low' => 'Düşük', default => 'Normal'
            };

            $events[] = [
                'title' => '📝 ' . $todo->title,
                'start' => $todo->due_date->toIso8601String(),
                'color' => $color,
                'allDay' => true,
                'extendedProps' => [
                    'eventType' => 'todo',
                    'model_type' => 'todo',
                    'id' => $todo->id,
                    'is_important' => ($todo->priority === 'high'),
                    'details' => [
                        'Görev' => $todo->title,
                        'Durum' => $todo->is_completed ? 'Tamamlandı' : 'Bekliyor',
                        'Öncelik' => $oncelikText,
                        'Son Tarih' => $todo->due_date->format('d.m.Y'),
                        'Açıklama' => $todo->description ?? 'Açıklama yok'
                    ]
                ]
            ];
        }
        return $events;
    }

    public function toggleImportant(Request $request)
    {
        $user = Auth::user();
        if (!$user || !$user->hasRole(['admin', 'yonetici', 'mudur'])) {
            return response()->json(['success' => false, 'message' => 'Bu işlem için yetkiniz yok.'], 403);
        }
        $validated = $request->validate([
            'model_type' => 'required|string',
            'model_id' => 'required|integer',
            'is_important' => 'required|boolean',
        ]);
        $modelId = $validated['model_id'];
        $isImportant = $request->boolean('is_important');

        try {
            $modelClass = match ($validated['model_type']) {
                'shipment' => Shipment::class,
                'production_plan' => ProductionPlan::class,
                'event' => Event::class,
                'vehicle_assignment' => VehicleAssignment::class,
                'travel' => Travel::class,
                'maintenance_plan' => MaintenancePlan::class,
                default => null,
            };

            if (!$modelClass)
                return response()->json(['success' => false, 'message' => 'Geçersiz veri türü.'], 400);

            // DÜZELTME: forUser kaldırıldı. Trait varsa otomatik, yoksa standart find.
            $record = $modelClass::find($modelId);

            if (!$record)
                return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı veya yetkiniz yok.'], 404);

            if ($validated['model_type'] === 'maintenance_plan') {
                $record->priority = $isImportant ? 'critical' : 'normal';
            } else {
                $record->is_important = $isImportant;
            }
            $record->save();

            return response()->json(['success' => true, 'message' => 'Durum güncellendi.', 'new_state' => $isImportant]);
        } catch (\Exception $e) {
            Log::error('ToggleImportant Hatası: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Sunucu hatası oluştu.'], 500);
        }
    }

    private function getCustomerVisitData($user, $start, $end)
    {
        $events = [];
        try {
            // CRM modülünden ziyaretleri çek
            $visits = \App\Models\CustomerVisit::with(['customer', 'representative'])
                ->whereBetween('visit_date', [$start, $end])
                ->get();

            foreach ($visits as $visit) {
                $statusColor = match ($visit->status) {
                    'done', 'completed' => '#198754', // Yeşil
                    'cancelled' => '#dc3545', // Kırmızı
                    default => '#0dcaf0', // Mavi (Info)
                };

                $detaylar = [
                    'Müşteri' => $visit->customer->name ?? 'Bilinmiyor',
                    'Temsilci' => $visit->representative->name ?? '-',
                    'Ziyaret Nedeni' => $visit->purpose ?? '-',
                    'Görüşülen Kişiler' => $visit->contact_persons ?? '-',
                    'Sonuç' => $visit->result ?? '-',
                    'Tarih' => $visit->visit_date ? \Carbon\Carbon::parse($visit->visit_date)->format('d.m.Y H:i') : '-'
                ];

                $events[] = [
                    'title' => '🤝 ' . ($visit->customer->name ?? 'Müşteri Ziyareti'),
                    'start' => \Carbon\Carbon::parse($visit->visit_date)->toIso8601String(),
                    'end' => \Carbon\Carbon::parse($visit->visit_date)->addHour()->toIso8601String(),
                    'color' => $statusColor,
                    'extendedProps' => [
                        'eventType' => 'customer_visit',
                        'model_type' => 'customer_visit',
                        'id' => $visit->id,
                        'is_important' => false,
                        'details' => $detaylar
                    ]
                ];
            }
        } catch (\Exception $e) {
            \Log::error('Takvim Müşteri Ziyareti Hatası', ['error' => $e->getMessage()]);
        }
        return $events;
    }
}