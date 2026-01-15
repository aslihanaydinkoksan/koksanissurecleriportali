<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shipment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\ProductionPlan;
use App\Models\Event;
use App\Models\VehicleAssignment;
use App\Models\Travel;
use Illuminate\Support\Facades\Gate;
use App\Models\Department;
use App\Models\MaintenancePlan;
use Illuminate\Support\Collection;
use App\Services\KanbanService;
use App\Services\StatisticsService;

class HomeController extends Controller
{
    protected $statsService;
    protected $kanbanService;

    public function __construct(StatisticsService $statsService, KanbanService $kanbanService)
    {
        $this->middleware('auth');
        $this->statsService = $statsService;
        $this->kanbanService = $kanbanService;
    }

    /**
     * ANA TAKVİM SAYFASI (Kişisel)
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $activeUnitId = session('active_unit_id') ?? $user->business_unit_id;

        // Görünüm için departman bilgisi
        $firstDept = $user->departments->first();
        $departmentSlug = $firstDept ? strtolower(trim($firstDept->slug)) : 'genel';
        $departmentName = $firstDept?->name ?? 'Genel';

        $kanbanBoards = collect();
        if ($activeUnitId) {
            $kanbanBoards = $this->kanbanService->getDashboardSummary($user->id, $activeUnitId);
        }

        $allEvents = [];
        $statsTitle = "Takvimim";

        // 1. Lojistik Verileri
        if ($user->can('view_logistics')) {
            $data = $this->getLojistikIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // 2. Üretim Verileri
        if ($user->can('view_production')) {
            $data = $this->getUretimIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // 3. Bakım Verileri
        if ($user->can('view_maintenance')) {
            $data = $this->getBakimIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // 4. İdari İşler / Hizmet Verileri
        if ($user->can('view_administrative')) {
            $data = $this->getHizmetIndexData($user);
            $allEvents = array_merge($allEvents, $data['events']);
        }

        // Todo modelinde Trait yoksa standart sorgu:
        $todos = \App\Models\Todo::where('user_id', $user->id)
            ->whereNotNull('due_date')
            ->where('is_completed', false)
            ->get();

        foreach ($todos as $todo) {
            // Renk Ataması
            $color = match ($todo->priority) {
                'high' => '#dc3545',   // Kırmızı
                'medium' => '#fd7e14', // Turuncu
                'low' => '#20c997',    // Yeşil
                default => '#6c757d'   // Gri
            };

            // Öncelik Yazısı (Türkçeleştirme)
            $oncelikText = match ($todo->priority) {
                'high' => 'Yüksek',
                'medium' => 'Orta',
                'low' => 'Düşük',
                default => 'Normal'
            };

            // Durum Yazısı
            $durumText = $todo->is_completed ? 'Tamamlandı' : 'Bekliyor';

            $allEvents[] = [
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
                        'Durum' => $durumText,
                        'Öncelik' => $oncelikText,
                        'Son Tarih' => $todo->due_date->format('d.m.Y'),
                        'Oluşturulma' => $todo->created_at ? $todo->created_at->format('d.m.Y H:i') : '-',
                        'Açıklama' => $todo->description ?? null
                    ]
                ]
            ];
        }

        // Kullanıcı Listesi
        $users = collect();
        if ($user->hasRole(['admin', 'yonetici', 'mudur'])) {
            $users = User::with('departments')->orderBy('name')->get();
        }
        $allEvents = collect($allEvents)->unique(function ($item) {
            return $item['extendedProps']['model_type'] . '-' . $item['extendedProps']['id'];
        })->values()->all();

        return view('home', [
            'users' => $users,
            'departmentName' => $departmentName,
            'departmentSlug' => $departmentSlug,
            'events' => $allEvents,
            'chartData' => [],
            'statsTitle' => $statsTitle,
            'kanbanBoards' => $kanbanBoards,
        ]);
    }

    /**
     * KARŞILAMA / DASHBOARD SAYFASI
     */
    public function welcome(Request $request)
    {
        $user = Auth::user();

        // TV ekranı yönlendirmesi
        if ($user && $user->email === 'tv@koksan.com') {
            return redirect()->route('tv.dashboard');
        }

        $activeUnitId = session('active_unit_id') ?? $user->businessUnits->first()?->id;

        // Kanban özeti yükle (Doğru parametrelerle)
        $kanboards = $user && $activeUnitId
            ? $this->kanbanService->getDashboardSummary($user->id, $activeUnitId)
            : collect();

        // Önemli Öğeler (Sağ Sidebar)
        $allItems = $this->getMappedImportantItems($request);
        $importantItems = $allItems->take(4);
        $importantItemsCount = $allItems->count();

        // Departman slug tespiti
        $firstDept = $user->departments->first();
        $departmentSlug = $firstDept ? trim($firstDept->slug) : ($user->isAdmin() ? 'admin' : null);

        // MİMARİ REFAKTÖR: Veri toplama mantığını ayırarak karmaşıklığı düşürüyoruz
        $dashboardData = $this->resolveDashboardData($user, $departmentSlug);

        return view('welcome', array_merge([
            'importantItems' => $importantItems,
            'importantItemsCount' => $importantItemsCount,
            'kanbanBoards' => $kanboards,
            'departmentSlug' => $departmentSlug,
            'chartType' => 'sankey',
        ], $dashboardData));
    }

    /**
     * Takvim üzerinde "Önemli" işaretleme işlemi
     */
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

            // DÜZELTME: forUser kaldırıldı. Trait (GlobalScope) sayesinde find() metodu
            // sadece aktif fabrikanın verisini bulabilir. Diğer fabrikalara erişemez.
            $record = $modelClass::find($modelId);

            if (!$record) {
                return response()->json(['success' => false, 'message' => 'Kayıt bulunamadı veya yetkiniz yok.'], 404);
            }

            if ($validated['model_type'] === 'maintenance_plan') {
                $record->priority = $isImportant ? 'critical' : 'normal';
            } else {
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

    public function showAllImportant(Request $request)
    {
        $filters = $request->only(['type', 'department_id', 'date_from', 'date_to']);
        $departments = Department::orderBy('name')->get();
        $allItems = $this->getMappedImportantItems($request);
        return view('important-items', [
            'importantItems' => $allItems,
            'filters' => $filters,
            'departments' => $departments
        ]);
    }

    // --- TAKVİM ve DASHBOARD İÇİN YARDIMCI FONKSİYONLAR ---

    private function getLojistikIndexData($user)
    {
        $events = [];
        $now = Carbon::now();
        // DÜZELTME: forUser kaldırıldı, with() kullanıldı
        $shipments = Shipment::with('onaylayanKullanici')->get()->unique('id');

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

            $onayUrl = ($user->hasRole('admin') || $user->hasRole('roles.lojistik_personeli'))
                ? route('shipments.onayla', $shipment->id)
                : null;

            $detaylar = [
                'Yük Tipi' => $shipment->shipment_type ?? 'Genel',
                'Araç Tipi' => $shipment->arac_tipi ?? 'Belirtilmedi',
                'Kargo İçeriği' => $shipment->kargo_icerigi,
                'Miktar' => ($shipment->kargo_miktari ?? '-') . ' ' . ($shipment->kargo_tipi ?? ''),
            ];

            $aracTipiLower = mb_strtolower($shipment->arac_tipi ?? '');
            $shipmentTypeLower = mb_strtolower($shipment->shipment_type ?? '');

            if (str_contains($aracTipiLower, 'gemi') || str_contains($shipmentTypeLower, 'deniz') || str_contains($aracTipiLower, 'ship')) {
                $detaylar['Gemi Adı'] = $shipment->gemi_adi ?? '-';
                $detaylar['IMO Numarası'] = $shipment->imo_numarasi ?? '-';
                $detaylar['Kalkış Limanı'] = $shipment->kalkis_limani ?? '-';
                $detaylar['Varış Limanı'] = $shipment->varis_limani ?? '-';
            } else {
                $detaylar['Plaka'] = $shipment->plaka ?? '-';
                if (!empty($shipment->dorce_plakasi)) {
                    $detaylar['Dorse Plaka'] = $shipment->dorce_plakasi;
                }
                $detaylar['Sürücü'] = $shipment->sofor_adi ?? '-';
                $detaylar['Kalkış Noktası'] = $shipment->kalkis_noktasi ?? '-';
                $detaylar['Varış Noktası'] = $shipment->varis_noktasi ?? '-';
                if (!empty($shipment->nakliye_firmasi)) {
                    $detaylar['Nakliye Firması'] = $shipment->nakliye_firmasi;
                }
            }

            $detaylar['Çıkış Tarihi'] = $cikisTarihi ? $cikisTarihi->format('d.m.Y H:i') : '-';
            $detaylar['Tahmini Varış'] = $varisTarihi ? $varisTarihi->format('d.m.Y H:i') : '-';
            $detaylar['Onay Durumu'] = $shipment->onaylanma_tarihi ? $shipment->onaylanma_tarihi : null;
            $detaylar['Onaylayan'] = $shipment->onaylayanKullanici->name ?? null;
            $detaylar['Açıklama'] = $shipment->aciklamalar ?? null;

            $extendedProps = ['eventType' => 'shipment', 'model_type' => 'shipment', 'id' => $shipment->id, 'is_important' => $shipment->is_important, 'title' => '🚚 ' . $shipment->kargo_icerigi, 'onayUrl' => $onayUrl, 'details' => $detaylar];

            if ($cikisTarihi)
                $events[] = ['title' => 'ÇIKIŞ: ' . $shipment->kargo_icerigi, 'start' => $cikisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
            if ($varisTarihi)
                $events[] = ['title' => 'VARIŞ: ' . $shipment->kargo_icerigi, 'start' => $varisTarihi->toIso8601String(), 'color' => $color, 'extendedProps' => $extendedProps];
        }
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "Sevkiyat Takvimi"];
    }

    private function getUretimIndexData($user)
    {
        $events = [];
        // DÜZELTME: forUser kaldırıldı
        $plans = ProductionPlan::with('user')->get()->unique('id');

        foreach ($plans as $plan) {
            $events[] = [
                'title' => 'Üretim: ' . $plan->plan_title,
                'start' => $plan->week_start_date->startOfDay()->toIso8601String(),
                'end' => $plan->week_start_date->copy()->addDay()->startOfDay()->toIso8601String(),
                'color' => '#4FD1C5',
                'extendedProps' => [
                    'eventType' => 'production',
                    'model_type' => 'production_plan',
                    'is_important' => $plan->is_important,
                    'id' => $plan->id,
                    'title' => $plan->plan_title,
                    'details' => ['Plan Detayları' => $plan->plan_details]
                ]
            ];
        }
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "Üretim Takvimi"];
    }

    private function getHizmetIndexData($user)
    {
        $events = [];
        // DÜZELTME: forUser kaldırıldı
        $serviceEvents = Event::with('user')->get()->unique('id');

        foreach ($serviceEvents as $event) {
            $detaylar = [
                'Etkinlik Başlığı' => $event->title,
                'Tür' => $event->event_type ?? 'Genel',
                'Konum' => $event->location ?? '-',
                'Başlangıç' => $event->start_datetime->format('d.m.Y H:i'),
                'Bitiş' => $event->end_datetime->format('d.m.Y H:i'),
            ];

            if ($event->customer_id) {
                $detaylar['Müşteri'] = $event->customer->name ?? ('Müşteri #' . $event->customer_id);
            }
            if (!empty($event->visit_purpose)) {
                $detaylar['Ziyaret Amacı'] = $event->visit_purpose;
            }
            $status = $event->visit_status ?? 'planlandi';
            $detaylar['Durum'] = ucfirst($status);

            if (strtolower($status) === 'iptal' || strtolower($status) === 'cancelled') {
                if (!empty($event->cancellation_reason)) {
                    $detaylar['İptal Nedeni'] = $event->cancellation_reason;
                }
            }
            if (!empty($event->after_sales_notes)) {
                $detaylar['Satış Sonrası Notlar'] = Str::limit($event->after_sales_notes, 50);
            }
            $detaylar['Açıklama'] = $event->description ?? null;

            $events[] = [
                'title' => 'Etkinlik: ' . $event->title,
                'start' => $event->start_datetime->format('Y-m-d\TH:i:s'),
                'end' => $event->end_datetime->format('Y-m-d\TH:i:s'),
                'color' => '#F093FB',
                'extendedProps' => [
                    'eventType' => 'service_event',
                    'model_type' => 'event',
                    'is_important' => $event->is_important,
                    'id' => $event->id,
                    'details' => $detaylar
                ]
            ];
        }

        // DÜZELTME: forUser kaldırıldı
        $assignments = VehicleAssignment::with(['vehicle', 'createdBy'])->get();

        foreach ($assignments as $assignment) {
            $aracBilgisi = $assignment->vehicle
                ? ($assignment->vehicle->plate_number . ' - ' . $assignment->vehicle->brand . ' ' . $assignment->vehicle->model)
                : 'Araç Bilgisi Yok';

            $gorevDetaylar = [
                'Araç' => $aracBilgisi,
                'Görev Tanımı' => $assignment->task_description,
                'Talep Eden' => $assignment->createdBy?->name ?? '-',
                'Sürücü' => $assignment->driver?->name ?? '-',
                'Başlangıç' => $assignment->start_time->format('d.m.Y H:i'),
                'Bitiş' => $assignment->end_time->format('d.m.Y H:i'),
                'Durum' => ucfirst($assignment->status)
            ];
            if (!empty($assignment->start_km)) {
                $gorevDetaylar['Başlangıç KM'] = $assignment->start_km;
            }

            $events[] = [
                'title' => 'Araç: ' . ($assignment->vehicle?->plate_number ?? '?') . ' - ' . Str::limit($assignment->task_description, 20),
                'start' => $assignment->start_time->format('Y-m-d\TH:i:s'),
                'end' => $assignment->end_time->format('Y-m-d\TH:i:s'),
                'color' => '#FBD38D',
                'extendedProps' => [
                    'eventType' => 'vehicle_assignment',
                    'model_type' => 'vehicle_assignment',
                    'is_important' => $assignment->is_important,
                    'id' => $assignment->id,
                    'details' => $gorevDetaylar
                ]
            ];
        }

        return ['events' => $events, 'chartData' => [], 'statsTitle' => "İdari İşler Takvimi"];
    }

    private function getBakimIndexData($user)
    {
        $events = [];
        // DÜZELTME: forUser kaldırıldı
        $plans = MaintenancePlan::with(['asset', 'type'])->get()->unique('id');

        foreach ($plans as $plan) {
            $color = match ($plan->status) {
                'pending' => '#F6E05E', 'in_progress' => '#3182CE', 'completed' => '#48BB78', 'cancelled' => '#E53E3E', default => '#A0AEC0',
            };
            $baslik = 'Bakım: ' . ($plan->asset->name ?? 'Varlık Silinmiş');
            if (!empty($plan->title)) {
                $baslik .= ' - ' . $plan->title;
            }

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

            if ($plan->actual_start_date) {
                $detaylar['Gerçekleşen Başlangıç'] = Carbon::parse($plan->actual_start_date)->format('d.m.Y H:i');
            }
            if ($plan->actual_end_date) {
                $detaylar['Gerçekleşen Bitiş'] = Carbon::parse($plan->actual_end_date)->format('d.m.Y H:i');
            }
            if (!empty($plan->completion_note)) {
                $detaylar['Sonuç Notu'] = $plan->completion_note;
            }
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
        return ['events' => $events, 'chartData' => [], 'statsTitle' => "Bakım Takvimi"];
    }

    private function getMappedImportantItems(Request $request)
    {
        $user = Auth::user();
        $typeFilter = $request->input('type', 'all');
        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');
        $deptFilter = $request->input('department_id', null);

        $allMappedItems = collect();

        // 1. SEVKİYAT (DÜZELTME: forUser kaldırıldı)
        if ($typeFilter == 'all' || $typeFilter == 'shipment') {
            $query = Shipment::where('is_important', true);
            if ($dateFrom)
                $query->where('tahmini_varis_tarihi', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('tahmini_varis_tarihi', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Sevkiyat: ' . ($item->kargo_icerigi ?? 'Detay Yok'),
                'date' => $item->tahmini_varis_tarihi,
                'model_id' => $item->id,
                'model_type' => 'shipment'
            ]));
        }

        // 2. ÜRETİM PLANI (DÜZELTME: forUser kaldırıldı)
        if ($typeFilter == 'all' || $typeFilter == 'production_plan') {
            $query = ProductionPlan::where('is_important', true);
            if ($dateFrom)
                $query->where('week_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('week_start_date', '<=', Carbon::parse($dateTo)->endOfDay());
            if ($deptFilter)
                $query->whereHas('user', fn($q) => $q->where('department_id', $deptFilter));

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Üretim: ' . $item->plan_title,
                'date' => $item->week_start_date,
                'model_id' => $item->id,
                'model_type' => 'production_plan'
            ]));
        }

        // 3. BAKIM PLANI (DÜZELTME: forUser kaldırıldı)
        if ($typeFilter == 'all' || $typeFilter == 'maintenance_plan') {
            $query = MaintenancePlan::whereIn('priority', ['high', 'critical']);
            if ($dateFrom)
                $query->where('planned_start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('planned_start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Bakım: ' . ($item->asset->name ?? 'Bilinmiyor') . ' (' . $item->title . ')',
                'date' => $item->planned_start_date,
                'model_id' => $item->id,
                'model_type' => 'maintenance_plan'
            ]));
        }

        // 4. ETKİNLİK (DÜZELTME: forUser kaldırıldı)
        if ($typeFilter == 'all' || $typeFilter == 'event') {
            $query = Event::where('is_important', true);
            if ($dateFrom)
                $query->where('start_datetime', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_datetime', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Etkinlik: ' . $item->title,
                'date' => $item->start_datetime,
                'model_id' => $item->id,
                'model_type' => 'event'
            ]));
        }

        // 5. ARAÇ GÖREVİ (DÜZELTME: forUser kaldırıldı)
        if ($typeFilter == 'all' || $typeFilter == 'vehicle_assignment') {
            $query = VehicleAssignment::where('is_important', true);
            if ($dateFrom)
                $query->where('start_time', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_time', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => 'Araç Görevi: ' . Str::limit($item->task_description, 30),
                'date' => $item->start_time,
                'model_id' => $item->id,
                'model_type' => 'vehicle_assignment'
            ]));
        }

        // 6. SEYAHAT (DÜZELTME: forUser kaldırıldı)
        if ($typeFilter == 'all' || $typeFilter == 'travel') {
            $query = Travel::where('is_important', true);
            if ($dateFrom)
                $query->where('start_date', '>=', Carbon::parse($dateFrom)->startOfDay());
            if ($dateTo)
                $query->where('start_date', '<=', Carbon::parse($dateTo)->endOfDay());

            $allMappedItems = $allMappedItems->merge($query->get()->map(fn($item) => (object) [
                'title' => '✈️ Seyahat: ' . Str::limit($item->name, 30),
                'date' => $item->start_date,
                'model_id' => $item->id,
                'model_type' => 'travel'
            ]));
        }

        // 7. GECİKEN GÖREVLER (DÜZELTME: forUser kaldırıldı)
        $overdueQuery = VehicleAssignment::where('start_time', '<', Carbon::today())
            ->whereIn('status', ['pending', 'in_progress']);

        $overdueItems = $overdueQuery->get()->map(fn($item) => (object) [
            'title' => '⚠️ GECİKEN GÖREV: ' . Str::limit($item->task_description, 40),
            'date' => $item->start_time,
            'model_id' => $item->id,
            'model_type' => 'vehicle_assignment',
            'is_overdue' => true
        ]);

        $allMappedItems = $allMappedItems->merge($overdueItems);

        return $allMappedItems->sortByDesc('date');
    }

    // --- WELCOME SANKEY GRAFİKLERİ İÇİN SERVİS ÇAĞRILARI (DÜZELTİLDİ) ---

    private function getLogisticsWelcomeData($user)
    {
        $welcomeTitle = "Yaklaşan Sevkiyatlar (Genel Bakış)";
        $chartTitle = "Kargo İçeriği -> Araç Tipi Akışı ";
        $chartData = [];

        // DÜZELTME: forUser kaldırıldı
        $todayItems = Shipment::whereBetween('tahmini_varis_tarihi', [Carbon::today()->startOfDay(), Carbon::today()->addDays(3)->endOfDay()])
            ->orderBy('tahmini_varis_tarihi', 'asc')->get();

        // DÜZELTME: forUser kaldırıldı
        $sankeyFlow = Shipment::select(['kargo_icerigi', 'arac_tipi', DB::raw('COUNT(*) as weight')])
            ->whereNotNull('kargo_icerigi')
            ->whereNotNull('arac_tipi')
            ->groupBy('kargo_icerigi', 'arac_tipi')
            ->having('weight', '>', 0)
            ->get();

        foreach ($sankeyFlow as $flow) {
            $normalizedKargo = $this->normalizeCargoContent($flow->kargo_icerigi);
            $normalizedArac = $this->normalizeVehicleType($flow->arac_tipi);
            $chartData[] = [strval($normalizedKargo), strval($normalizedArac), (int) $flow->weight];
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Sevkiyat Girilmedi', 1];
        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getProductionWelcomeData($user)
    {
        $welcomeTitle = "Bugün Başlayan Üretim Planları";
        $chartTitle = "Makine -> Ürün Planlama Akışı (Toplam Adet)";
        $chartData = [];

        // DÜZELTME: forUser kaldırıldı
        $todayItems = ProductionPlan::whereBetween('week_start_date', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
            ->orderBy('week_start_date', 'asc')->get();

        // DÜZELTME: forUser kaldırıldı
        $plans = ProductionPlan::whereNotNull('plan_details')->get();

        $flowCounts = [];
        foreach ($plans as $plan) {
            if (is_array($plan->plan_details)) {
                foreach ($plan->plan_details as $detail) {
                    $machine = trim(strval($detail['machine'] ?? 'Bilinmiyor'));
                    $productRaw = $detail['product'] ?? 'Bilinmiyor';
                    $product = is_numeric($productRaw) ? 'Ürün-' . $productRaw : trim(strval($productRaw));
                    $quantity = (int) ($detail['quantity'] ?? 0);
                    if ($machine !== 'Bilinmiyor' && $product !== 'Bilinmiyor' && $quantity > 0) {
                        if (!isset($flowCounts[$machine]))
                            $flowCounts[$machine] = [];
                        if (!isset($flowCounts[$machine][$product]))
                            $flowCounts[$machine][$product] = 0;
                        $flowCounts[$machine][$product] += $quantity;
                    }
                }
            }
        }
        foreach ($flowCounts as $machine => $products) {
            foreach ($products as $product => $weight) {
                $chartData[] = [strval($machine), strval($product), (int) $weight];
            }
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Plan Girilmedi', 1];

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getServiceWelcomeData($user)
    {
        $welcomeTitle = "Hizmet ve Operasyon Yönetimi";
        $chartTitle = "Etkinlik Durumu & Seyahat Dağılımı";

        // DÜZELTME: forUser kaldırıldı
        $todayEvents = Event::whereDate('start_datetime', Carbon::today())->orderBy('start_datetime', 'asc')->get();
        $todayAssignments = VehicleAssignment::whereDate('start_time', Carbon::today())->with('vehicle')->orderBy('start_time', 'asc')->get();
        $todayTravels = Travel::whereDate('start_date', Carbon::today())->orderBy('start_date', 'asc')->get();

        $todayItems = $todayEvents->merge($todayAssignments)->merge($todayTravels)->sortBy(fn($item) => $item->start_datetime ?? $item->start_time ?? $item->start_date);

        // Chart Verisi (DÜZELTME: forUser kaldırıldı)
        $chartData = [];
        $eventStats = Event::selectRaw('event_type, visit_status, count(*) as total')
            ->groupBy('event_type', 'visit_status')
            ->get();

        foreach ($eventStats as $stat) {
            $source = $stat->event_type ? ucfirst($stat->event_type) : 'Diğer Etkinlikler';
            $target = match ($stat->visit_status) {
                'planlandi' => 'Planlandı', 'gerceklesti' => 'Gerçekleşti', 'iptal' => 'İptal', 'ertelendi' => 'Ertelendi', default => 'Durum Belirsiz'
            };
            if ($source === $target)
                $target .= ' ';
            $chartData[] = [strval($source), strval($target), (int) $stat->total];
        }

        $bookingStats = DB::table('bookings')
            ->select('type', DB::raw('count(*) as total'))
            ->groupBy('type')
            ->get();

        foreach ($bookingStats as $stat) {
            $source = 'Seyahat Planlaması';
            $target = match ($stat->type) {
                'flight' => 'Uçak Bileti', 'hotel' => 'Otel Konaklama', 'bus' => 'Otobüs/Transfer', 'car' => 'Araç Kiralama', default => 'Diğer Rezervasyon'
            };
            $chartData[] = [$source, $target, (int) $stat->total];
        }

        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Kayıt Bulunamadı', 1];

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getMaintenanceWelcomeData($user)
    {
        $welcomeTitle = "Bugünkü Bakım Planları";
        $chartTitle = "Bakım Türü -> Varlık Akışı";
        $chartData = [];

        // DÜZELTME: forUser kaldırıldı
        $todayItems = MaintenancePlan::with(['asset', 'type'])
            ->whereBetween('planned_start_date', [Carbon::today()->startOfDay(), Carbon::today()->addDays(2)->endOfDay()])
            ->orderBy('planned_start_date', 'asc')->get();

        // DÜZELTME: forUser kaldırıldı
        $plans = MaintenancePlan::with(['type', 'asset'])->get();

        $flowCounts = [];
        foreach ($plans as $plan) {
            $source = $plan->type->name ?? 'Diğer';
            $target = $plan->asset->name ?? 'Bilinmiyor';
            if (!isset($flowCounts[$source]))
                $flowCounts[$source] = [];
            if (!isset($flowCounts[$source][$target]))
                $flowCounts[$source][$target] = 0;
            $flowCounts[$source][$target]++;
        }
        foreach ($flowCounts as $source => $targets) {
            foreach ($targets as $target => $weight) {
                $chartData[] = [strval($source), strval($target), (int) $weight];
            }
        }
        if (empty($chartData))
            $chartData[] = ['Veri Yok', 'Henüz Plan Girilmedi', 1];

        return [$welcomeTitle, $chartTitle, $todayItems, $chartData];
    }

    private function getAdminDashboardData($user, $today, $weekStart, $weekEnd, $monthStart, $monthEnd)
    {
        // Admin Dashboard Verileri - DÜZELTME: forUser kaldırıldı
        $kpiData = [
            'sevkiyat_sayisi' => Shipment::whereDate('tahmini_varis_tarihi', $today)->count(),
            'plan_sayisi' => ProductionPlan::whereDate('week_start_date', $today)->count(),
            'etkinlik_sayisi' => Event::whereDate('start_datetime', $today)->count(),
            'arac_gorevi_sayisi' => VehicleAssignment::whereDate('start_time', $today)->count(),
            'bakim_sayisi' => MaintenancePlan::whereDate('planned_start_date', $today)->count(),
            'kullanici_sayisi' => User::count()
        ];

        // Chart Data - DÜZELTME: forUser kaldırıldı
        $chartData = [];
        $allLojistik = Shipment::count();
        $allUretim = ProductionPlan::count();
        $allEtkinlik = Event::count();
        $allBakim = MaintenancePlan::count();

        if ($allLojistik > 0)
            $chartData[] = ['Lojistik', 'Sevkiyatlar', $allLojistik];
        if ($allUretim > 0)
            $chartData[] = ['Üretim', 'Planlar', $allUretim];
        if ($allEtkinlik > 0)
            $chartData[] = ['İdari İşler', 'Etkinlikler', $allEtkinlik];
        if ($allBakim > 0)
            $chartData[] = ['Bakım', 'Bakım Planları', $allBakim];

        if (empty($chartData))
            $chartData[] = ['Sistem', 'Henüz Kayıt Yok', 1];

        return [
            'welcomeTitle' => session('active_unit_name', 'Genel') . " Özeti",
            'chartTitle' => "Departman Dağılımı",
            'todayItems' => collect(),
            'weeklyItems' => collect(),
            'monthlyItems' => collect(),
            'kpiData' => $kpiData,
            'chartData' => $chartData
        ];
    }

    /**
     * BİLDİRİM OKUMA VE YÖNLENDİRME
     */
    public function readNotification($id)
    {
        $notification = auth()->user()->unreadNotifications->where('id', $id)->first();

        if ($notification) {
            $notification->markAsRead();
            return redirect($notification->data['link'] ?? route('home'));
        }

        return back();
    }

    /**
     * TÜM BİLDİRİMLERİ OKUNDU YAP
     */
    public function readAllNotifications()
    {
        auth()->user()->unreadNotifications->markAsRead();
        return back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');
    }

    /**
     * AJAX İLE BİLDİRİM KONTROLÜ
     */
    public function checkNotifications()
    {
        $notifications = auth()->user()->unreadNotifications;
        $count = $notifications->count();
        $html = '';

        if ($count > 0) {
            foreach ($notifications as $notification) {
                $url = route('notifications.read', $notification->id);
                $icon = $notification->data['icon'] ?? 'fa-info-circle';
                $color = $notification->data['color'] ?? 'primary';
                $title = $notification->data['title'] ?? 'Bildirim';
                $message = $notification->data['message'] ?? '';
                $time = $notification->created_at->diffForHumans();

                $html .= '
                <a href="' . $url . '" class="list-group-item list-group-item-action p-3 border-bottom-0 d-flex align-items-start">
                    <div class="me-3 mt-1 text-' . $color . '">
                        <i class="fa-solid ' . $icon . ' fa-lg"></i>
                    </div>
                    <div class="flex-grow-1">
                        <div class="small fw-bold text-dark mb-1">' . $title . '</div>
                        <p class="mb-1 small text-muted lh-sm">' . $message . '</p>
                        <small class="text-secondary fw-bold" style="font-size: 0.7rem;">' . $time . '</small>
                    </div>
                </a>';
            }
        } else {
            $html = '
            <div class="p-4 text-center text-muted">
                <i class="fa-regular fa-bell-slash fa-2x mb-3 text-secondary opacity-50"></i>
                <p class="mb-0 small fw-medium">Şu an yeni bildiriminiz yok.</p>
            </div>';
        }

        return response()->json([
            'count' => $count,
            'html' => $html
        ]);
    }

    public function switchUnit(Request $request)
    {
        $request->validate([
            'unit_id' => 'required|exists:business_units,id'
        ]);

        $user = auth()->user();
        $unitId = $request->unit_id;
        $isAuthorized = $user->isAdmin() || $user->businessUnits->contains('id', $unitId);

        if (!$isAuthorized) {
            abort(403, 'Bu birime erişim yetkiniz yok.');
        }

        $unit = \App\Models\BusinessUnit::findOrFail($unitId);

        session([
            'active_unit_id' => $unit->id,
            'active_unit_name' => $unit->name
        ]);

        return back()->with('success', "Çalışma alanı {$unit->name} olarak değiştirildi.");
    }

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
    /**
     * Dashboard verilerini departmana göre çözen yardımcı metod (Complexity Reducer)
     */
    private function resolveDashboardData($user, $departmentSlug): array
    {
        $today = Carbon::today();
        $weekStart = Carbon::now()->startOfWeek();
        $weekEnd = Carbon::now()->endOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        $monthEnd = Carbon::now()->endOfMonth();

        // Varsayılan değerler
        $data = [
            'welcomeTitle' => "Hoş Geldiniz",
            'chartTitle' => "Genel Bakış",
            'chartData' => [],
            'kpiData' => [],
            'todayItems' => collect(),
            'weeklyItems' => collect(),
            'monthlyItems' => collect(),
        ];

        // Departman bazlı veri yükleme logic'ini private metodlara dağıtıyoruz
        switch ($departmentSlug) {
            case 'uretim':
                list($data['welcomeTitle'], $data['chartTitle'], $data['todayItems'], $data['chartData']) = $this->getProductionWelcomeData($user);
                break;
            case 'hizmet':
            case 'ulastirma':
                // Hizmet ve ulaştırma verilerini getServiceWelcomeData metodundan alıyoruz
                list($data['welcomeTitle'], $data['chartTitle'], $data['todayItems'], $data['chartData']) = $this->getServiceWelcomeData($user);
                break;
            case 'lojistik':
                list($data['welcomeTitle'], $data['chartTitle'], $data['todayItems'], $data['chartData']) = $this->getLogisticsWelcomeData($user);
                break;
            case 'bakim':
                list($data['welcomeTitle'], $data['chartTitle'], $data['todayItems'], $data['chartData']) = $this->getMaintenanceWelcomeData($user);
                break;
            default:
                $adminData = $this->getAdminDashboardData($user, $today, $weekStart, $weekEnd, $monthStart, $monthEnd);
                $data = array_merge($data, $adminData);
                break;
        }

        return $data;
    }
}