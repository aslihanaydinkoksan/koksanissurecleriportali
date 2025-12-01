<?php

namespace App\Http\Controllers;

use App\Models\Travel;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Log;
use App\Notifications\NewTravelPlanNotification;

class TravelController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. TÜMÜNÜ GÖRME YETKİSİ KONTROLÜ
        // Admin, Global Yönetici VEYA Ulaştırma Müdürü tüm seyahatleri görebilir.
        $canViewAll = $user->role === 'admin' ||
            $user->can('is-global-manager') ||
            ($user->department && $user->department->slug === 'ulastirma');

        $filters = [
            'name' => $request->input('name'),
            'status' => $request->input('status', 'all'),
            'is_important' => $request->input('is_important', 'all'),
            'date_from' => $request->input('date_from'),
            'date_to' => $request->input('date_to'),
            'user_id' => $request->input('user_id', 'all'),
        ];

        $travelsQuery = Travel::with('user');

        // --- FİLTRELER ---
        if (!empty($filters['name'])) {
            $travelsQuery->where('name', 'LIKE', '%' . $filters['name'] . '%');
        }
        if ($filters['status'] !== 'all') {
            $travelsQuery->where('status', $filters['status']);
        }
        if ($filters['is_important'] !== 'all') {
            $travelsQuery->where('is_important', $filters['is_important'] === 'yes');
        }
        if (!empty($filters['date_from'])) {
            $travelsQuery->where('end_date', '>=', Carbon::parse($filters['date_from'])->startOfDay());
        }
        if (!empty($filters['date_to'])) {
            $travelsQuery->where('start_date', '<=', Carbon::parse($filters['date_to'])->endOfDay());
        }

        // --- YETKİ FİLTRESİ ---

        // Eğer "Tümünü Görme Yetkisi" YOKSA, sadece kendi seyahatlerini görsün
        if (!$canViewAll) {
            $travelsQuery->where('user_id', $user->id);
        }
        // Eğer yetkisi varsa VE filtrede belirli bir kullanıcı seçildiyse ona göre süz
        elseif ($filters['user_id'] !== 'all') {
            $travelsQuery->where('user_id', $filters['user_id']);
        }

        $travels = $travelsQuery->orderBy('start_date', 'desc')
            ->paginate(20)
            ->appends($filters);

        // Kullanıcı Listesi (Filtreleme için)
        $users = collect();
        if ($canViewAll) {
            // Seyahat oluşturma potansiyeli olan herkesi listele (Hizmet ve Adminler)
            // Ulaştırma Müdürü de filtreleme yapabilsin diye bu listeyi görüyor
            $users = User::whereHas('department', fn($q) => $q->where('slug', 'hizmet'))
                ->orWhere('role', 'admin')
                ->orderBy('name')
                ->get();
        }

        return view('travels.index', compact('travels', 'filters', 'users'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role !== 'admin' && (!$user->department || $user->department->slug !== 'hizmet')) {
            abort(403, 'Seyahat planı oluşturma yetkiniz bulunmamaktadır. Lütfen İdari İşler ile görüşün.');
        }
        return view('travels.create');
    }

    public function store(Request $request)
    {
        // 1. Validasyon
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,completed',
            'is_important' => 'nullable|boolean',
        ]);

        $validatedData['user_id'] = Auth::id();
        $validatedData['is_important'] = $request->has('is_important');

        // 2. Kayıt
        $travel = Travel::create($validatedData);

        // 3. Bildirim Gönderme (Temiz Hali)
        try {
            // Bildirimi alacaklar: Adminler + Ulaştırma Müdürleri
            $recipients = User::where(function ($query) {
                $query->where('role', 'admin')
                    ->orWhere(function ($q) {
                        $q->where('role', 'müdür')
                            ->whereHas('department', function ($d) {
                                $d->where('slug', 'ulastirma');
                            });
                    });
            })->get();

            if ($recipients->count() > 0) {
                Notification::send($recipients, new NewTravelPlanNotification($travel));
            }
        } catch (\Exception $e) {
            // Sadece hata olursa loga yazsın, kullanıcıya yansıtmasın
            Log::error("Seyahat bildirim hatası: " . $e->getMessage());
        }

        return redirect()->route('travels.show', $travel)
            ->with('success', 'Seyahat planı başarıyla oluşturuldu.');
    }

    public function show(Travel $travel)
    {
        $user = Auth::user();

        // Görüntüleme Yetkisi: Oluşturan, Admin veya Ulaştırma Müdürü
        $canView = $user->id === $travel->user_id ||
            $user->can('is-global-manager') ||
            $user->role === 'admin' ||
            ($user->role === 'müdür' && $user->department && $user->department->slug === 'ulastirma');

        if (!$canView) {
            abort(403, 'Bu seyahati görüntüleme yetkiniz yok.');
        }

        $travel->load([
            'bookings.media',
            'customerVisits.customer',
            'customerVisits.event'
        ]);
        $travelActivities = $travel->activities()->latest()->get();

        return view('travels.show', compact('travel', 'travelActivities'));
    }

    public function edit(Travel $travel)
    {
        // Düzenleme Yetkisi: SADECE Oluşturan veya Admin (Ulaştırma müdürü düzenleyemez, sadece görür)
        if (Auth::id() !== $travel->user_id && !Auth::user()->can('is-global-manager') && Auth::user()->role !== 'admin') {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        return view('travels.edit', compact('travel'));
    }

    public function update(Request $request, Travel $travel)
    {
        if (Auth::id() !== $travel->user_id && !Auth::user()->can('is-global-manager') && Auth::user()->role !== 'admin') {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status' => 'required|in:planned,completed',
            'is_important' => 'nullable|boolean',
        ]);

        $validatedData['is_important'] = $request->has('is_important');
        $travel->update($validatedData);

        return redirect()->route('travels.index')
            ->with('success', 'Seyahat planı başarıyla güncellendi.');
    }

    public function destroy(Travel $travel)
    {
        if (Auth::id() !== $travel->user_id && !Auth::user()->can('is-global-manager') && Auth::user()->role !== 'admin') {
            abort(403, 'Bu eylemi gerçekleştirme yetkiniz yok.');
        }

        $travel->delete();

        return redirect()->route('travels.index')
            ->with('success', 'Seyahat planı başarıyla silindi.');
    }
}