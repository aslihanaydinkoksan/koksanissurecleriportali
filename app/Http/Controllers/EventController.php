<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Customer;
use App\Models\Travel;
use App\Models\CustomerVisit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class EventController extends Controller
{
    private $eventTypes = [
        'toplanti' => 'Toplantı',
        'egitim' => 'Eğitim',
        'fuar' => 'Fuar',
        'gezi' => 'Gezi',
        'musteri_ziyareti' => 'Müşteri Ziyareti',
        'misafir_karsilama' => 'Misafir Karşılama',
        'diger' => 'Diğer',
    ];

    public function getEventTypes(): array
    {
        return $this->eventTypes;
    }

    /**
     * YARDIMCI METOD: Yetki Kontrolü
     * authorize() yerine bunu kullanacağız çünkü gate tarafındaki hatayı baypas ediyoruz.
     */
    private function checkAuth()
    {
        $user = Auth::user();
        // 1. Slug'daki boşlukları temizle
        $slug = $user->department ? trim($user->department->slug) : '';

        // 2. Rolleri kontrol et (Hem 'yonetici' hem 'yönetici' ekledik ki hata olmasın)
        $isAuthorizedRole = in_array($user->role, ['admin', 'yonetici', 'yönetici']);

        // 3. Admin, Yönetici VEYA Hizmet departmanı girebilsin
        if (!$isAuthorizedRole && $slug !== 'hizmet') {
            abort(403, 'Bu sayfaya erişim yetkiniz bulunmamaktadır.');
        }
    }

    public function index(Request $request)
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        $query = Event::with('user');
        $user = Auth::user();
        $isImportantFilter = $request->input('is_important', 'all');

        // Rol kontrolünü güncelledik: 'yonetici' ve 'yönetici' ikisi de eklendi
        if ($isImportantFilter !== 'all' && $user && in_array($user->role, ['admin', 'yonetici', 'yönetici'])) {
            if ($isImportantFilter === 'yes') {
                $query->where('is_important', true);
            } elseif ($isImportantFilter === 'no') {
                $query->where('is_important', false);
            }
        }

        // --- FİLTRELEME MANTIĞI ---
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
        }

        if ($request->filled('event_type') && $request->input('event_type') !== 'all') {
            $query->where('event_type', $request->input('event_type'));
        }

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where(
                    fn($q) =>
                    $q->where('start_datetime', '>=', $dateFrom)
                        ->orWhere('end_datetime', '>=', $dateFrom)
                );
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where(
                    fn($q) =>
                    $q->where('start_datetime', '<=', $dateTo)
                        ->orWhere('end_datetime', '<=', $dateTo)
                );
            } catch (\Exception $e) {
            }
        }
        // --- FİLTRELEME SONU ---

        $events = $query->orderBy('start_datetime', 'desc')->paginate(15);
        $filters = $request->only(['title', 'event_type', 'date_from', 'date_to', 'is_important']);
        $eventTypes = $this->eventTypes;

        return view('service.events.index', compact('events', 'filters', 'eventTypes'));
    }

    public function create()
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        $eventTypes = $this->eventTypes;
        $customers = Customer::orderBy('name')->get();
        $availableTravels = Travel::where('status', '!=', 'completed')
            ->where('user_id', Auth::id())
            ->orderBy('start_date', 'desc')
            ->get();

        return view('service.events.create', compact('eventTypes', 'customers', 'availableTravels'));
    }

    public function store(Request $request)
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'location' => 'nullable|string|max:255',
            'event_type' => ['required', Rule::in(array_keys($this->eventTypes))],
            'travel_id' => 'nullable|integer|exists:travels,id',
            'customer_id' => [
                'nullable',
                'integer',
                'exists:customers,id',
                Rule::requiredIf($request->event_type === 'musteri_ziyareti')
            ],
            'visit_purpose' => [
                'nullable',
                Rule::in(['satis_sonrasi_hizmet', 'egitim', 'rutin_ziyaret', 'pazarlama', 'diger']),
                Rule::requiredIf($request->event_type === 'musteri_ziyareti')
            ],
            'customer_machine_id' => 'nullable|integer|exists:customer_machines,id',
            'after_sales_notes' => 'nullable|string',
            'visit_status' => 'nullable|string|in:planlandi,gerceklesti,ertelendi,iptal',
            'cancellation_reason' => 'nullable|required_if:visit_status,iptal,ertelendi|string',
        ]);

        try {
            DB::beginTransaction();

            $event = Event::create([
                'user_id' => Auth::id(),
                'title' => $validatedData['title'],
                'description' => $validatedData['description'],
                'start_datetime' => $validatedData['start_datetime'],
                'end_datetime' => $validatedData['end_datetime'],
                'location' => $validatedData['location'],
                'event_type' => $validatedData['event_type'],
                'customer_id' => $validatedData['customer_id'] ?? null,
                'visit_status' => $validatedData['visit_status'] ?? 'planlandi',
                'cancellation_reason' => $validatedData['cancellation_reason'] ?? null,
            ]);

            if ($event->event_type === 'musteri_ziyareti') {
                CustomerVisit::create([
                    'event_id' => $event->id,
                    'customer_id' => $validatedData['customer_id'],
                    'travel_id' => $validatedData['travel_id'] ?? null,
                    'visit_purpose' => $validatedData['visit_purpose'],
                    'customer_machine_id' => $validatedData['customer_machine_id'] ?? null,
                    'after_sales_notes' => $validatedData['after_sales_notes'] ?? null,
                ]);
            }

            DB::commit();

            if ($event->event_type === 'fuar') {
                return redirect()->route('service.events.show', $event)
                    ->with('success', 'Fuar etkinliği oluşturuldu. Rezervasyonları ekleyebilirsiniz.');
            }

            return redirect()->route('service.events.index')
                ->with('success', 'Yeni etkinlik başarıyla oluşturuldu!');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Hata: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show(Event $event)
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        $event->load(['user', 'bookings.media', 'customerVisit.customer']);
        return view('service.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        // Rol kontrolünü güncelledik (yonetici + yönetici)
        if (Auth::id() !== $event->user_id && !in_array(Auth::user()->role, ['admin', 'yonetici', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu etkinliği sadece oluşturan kişi düzenleyebilir.');
        }

        $eventTypes = $this->eventTypes;
        $customers = Customer::orderBy('name')->get();
        $availableTravels = Travel::where('status', '!=', 'completed')
            ->where('user_id', Auth::id())
            ->get();
        $visitDetail = CustomerVisit::where('event_id', $event->id)->first();

        return view('service.events.edit', compact('event', 'eventTypes'));
    }

    public function update(Request $request, Event $event)
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        // Rol kontrolünü güncelledik (yonetici + yönetici)
        if (Auth::id() !== $event->user_id && !in_array(Auth::user()->role, ['admin', 'yonetici', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu etkinliği sadece oluşturan kişi düzenleyebilir.');
        }

        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'location' => 'nullable|string|max:255',
            'event_type' => ['required', Rule::in(array_keys($this->eventTypes))],
            'visit_status' => 'nullable|string|in:planlandi,gerceklesti,ertelendi,iptal',
            'cancellation_reason' => 'nullable|required_if:visit_status,iptal,ertelendi|string',
            'customer_id' => 'nullable|exists:customers,id',
        ]);

        $event->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'start_datetime' => $validatedData['start_datetime'],
            'end_datetime' => $validatedData['end_datetime'],
            'location' => $validatedData['location'],
            'event_type' => $validatedData['event_type'],
            'visit_status' => $validatedData['visit_status'] ?? $event->visit_status,
            'cancellation_reason' => $validatedData['cancellation_reason'] ?? null,
            'customer_id' => $validatedData['customer_id'] ?? $event->customer_id,
        ]);

        return redirect()->route('service.events.index')
            ->with('success', 'Etkinlik başarıyla güncellendi.');
    }

    public function destroy(Event $event)
    {
        // GÜNCEL YETKİ KONTROLÜ
        $this->checkAuth();

        // Rol kontrolünü güncelledik (yonetici + yönetici)
        if (Auth::id() !== $event->user_id && !in_array(Auth::user()->role, ['admin', 'yonetici', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu etkinliği sadece oluşturan kişi silebilir.');
        }

        $event->delete();

        return redirect()->route('service.events.index')
            ->with('success', 'Etkinlik başarıyla silindi.');
    }
}