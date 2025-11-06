<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class EventController extends Controller
{
    /**
     * Sabit etkinlik tiplerimiz. 
     * Formlarda (create, edit) ve validasyonda (store, update)
     * ve filtrelemede (index) bu listeyi kullanacağız.
     */
    private $eventTypes = [
        'toplanti' => 'Toplantı',
        'egitim' => 'Eğitim',
        'fuar' => 'Fuar',
        'gezi' => 'Gezi',
        'musteri_ziyareti' => 'Müşteri Ziyareti',
        'misafir_karsilama' => 'Misafir Karşılama',
        'diger' => 'Diğer',
    ];
    // **** YENİ EKLENECEK METOD BAŞLANGICI ****
    /**
     * Etkinlik tiplerini dizi olarak döndürür.
     * HomeController gibi başka yerlerden erişmek için kullanılır.
     *
     * @return array
     */
    public function getEventTypes(): array
    {
        return $this->eventTypes;
    }
    // **** YENİ EKLENECEK METOD BİTİŞİ ****

    /**
     * Etkinlikleri listeler ve filtreler.
     */
    public function index(Request $request)
    {
        // YETKİ KONTROLÜ: Sadece 'hizmet' birimi erişebilir
        $this->authorize('access-department', 'hizmet');

        $query = Event::with('user');
        $user = Auth::user(); // Giriş yapan kullanıcıyı al
        $isImportantFilter = $request->input('is_important', 'all');

        // Bu filtreyi sadece admin veya yönetici ise uygula
        if ($isImportantFilter !== 'all' && $user && in_array($user->role, ['admin', 'yönetici'])) {

            if ($isImportantFilter === 'yes') {
                $query->where('is_important', true);
            } elseif ($isImportantFilter === 'no') {
                $query->where('is_important', false);
            }
            // 'all' ise hiçbir şey yapma
        }

        // --- FİLTRELEME MANTIĞI ---
        if ($request->filled('title')) {
            $query->where('title', 'LIKE', '%' . $request->input('title') . '%');
        }

        // Etkinlik tipi filtresi
        if ($request->filled('event_type') && $request->input('event_type') !== 'all') {
            $query->where('event_type', $request->input('event_type'));
        }

        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                // Başlangıç veya Bitiş tarihi bu aralıkta olanlar
                $query->where(
                    fn($q) =>
                    $q->where('start_datetime', '>=', $dateFrom)
                        ->orWhere('end_datetime', '>=', $dateFrom)
                );
            } catch (\Exception $e) { /* Geçersiz tarihi yoksay */
            }
        }

        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                // Başlangıç veya Bitiş tarihi bu aralıkta olanlar
                $query->where(
                    fn($q) =>
                    $q->where('start_datetime', '<=', $dateTo)
                        ->orWhere('end_datetime', '<=', $dateTo)
                );
            } catch (\Exception $e) { /* Geçersiz tarihi yoksay */
            }
        }
        // --- FİLTRELEME SONU ---

        $events = $query->orderBy('start_datetime', 'desc')
            ->paginate(15);

        $filters = $request->only(['title', 'event_type', 'date_from', 'date_to', 'is_important']);

        // Sabit listemizi view'a gönderiyoruz
        $eventTypes = $this->eventTypes;

        return view('service.events.index', compact('events', 'filters', 'eventTypes'));
    }

    /**
     * Yeni bir etkinlik oluşturma formunu gösterir.
     */
    public function create()
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');

        $eventTypes = $this->eventTypes;
        return view('service.events.create', compact('eventTypes'));
    }

    /**
     * Yeni etkinliği veritabanında saklar.
     */
    public function store(Request $request)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');

        // VALIDASYON
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime', // Bitiş, başlangıçtan sonra olmalı
            'location' => 'nullable|string|max:255',
            'event_type' => ['required', Rule::in(array_keys($this->eventTypes))], // Listemizde var mı?
        ]);

        $validatedData['user_id'] = Auth::id();

        Event::create($validatedData);

        return redirect()->route('service.events.create')
            ->with('success', 'Yeni etkinlik başarıyla oluşturuldu!');
    }

    /**
     * Belirtilen etkinliği düzenleme formunu gösterir.
     */
    public function edit(Event $event)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');
        if (Auth::id() !== $event->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu etkinliği sadece oluşturan kişi düzenleyebilir.');
        }

        $eventTypes = $this->eventTypes;
        return view('service.events.edit', compact('event', 'eventTypes'));
    }

    /**
     * Veritabanındaki belirtilen etkinliği günceller.
     */
    public function update(Request $request, Event $event)
    {
        // YETKİ KONTROLÜ
        $this->authorize('access-department', 'hizmet');
        if (Auth::id() !== $event->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu etkinliği sadece oluşturan kişi düzenleyebilir.');
        }

        // Validasyon (store ile aynı)
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start_datetime' => 'required|date',
            'end_datetime' => 'required|date|after_or_equal:start_datetime',
            'location' => 'nullable|string|max:255',
            'event_type' => ['required', Rule::in(array_keys($this->eventTypes))],
        ]);

        $event->update($validatedData);

        return redirect()->route('service.events.index')
            ->with('success', 'Etkinlik başarıyla güncellendi.');
    }

    /**
     * Belirtilen etkinliği veritabanından siler.
     */
    public function destroy(Event $event)
    {
        // YETKİ KONTROLÜ (Adım 1): 'hizmet' birimi
        $this->authorize('access-department', 'hizmet');
        if (Auth::id() !== $event->user_id && !in_array(Auth::user()->role, ['admin', 'yönetici'])) {
            return redirect()->route('home')
                ->with('error', 'Bu etkinliği sadece oluşturan kişi silebilir.');
        }

        $event->delete();

        return redirect()->route('service.events.index')
            ->with('success', 'Etkinlik başarıyla silindi.');
    }
}
