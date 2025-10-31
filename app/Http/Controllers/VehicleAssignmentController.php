<?php

namespace App\Http\Controllers;

use App\Models\VehicleAssignment;
use App\Models\Vehicle;
use App\Models\ServiceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Carbon\Carbon;
use Illuminate\Support\Facades\Gate;

class VehicleAssignmentController extends Controller
{
    /**
     * Araç atamalarını listeler ve filtreler.
     */
    public function index(Request $request)
    {
        // $this->authorize('access-department', 'hizmet');

        $query = VehicleAssignment::with(['vehicle', 'user']);

        // --- FİLTRELEME ---
        if ($request->filled('vehicle_id')) {
            $query->where('vehicle_id', $request->input('vehicle_id'));
        }
        if ($request->filled('task_description')) {
            $query->where('task_description', 'LIKE', '%' . $request->input('task_description') . '%');
        }
        if ($request->filled('date_from')) {
            try {
                $dateFrom = Carbon::parse($request->input('date_from'))->startOfDay();
                $query->where('end_time', '>=', $dateFrom);
            } catch (\Exception $e) { /* Hatalı tarihi yoksay */
            }
        }
        if ($request->filled('date_to')) {
            try {
                $dateTo = Carbon::parse($request->input('date_to'))->endOfDay();
                $query->where('start_time', '<=', $dateTo);
            } catch (\Exception $e) { /* Hatalı tarihi yoksay */
            }
        }
        // --- FİLTRELEME SONU ---

        $assignments = $query->orderBy('start_time', 'desc')->paginate(15);
        $filters = $request->only(['vehicle_id', 'task_description', 'date_from', 'date_to']);
        $vehicles = Vehicle::where('is_active', true)->orderBy('plate_number')->get();

        return view('service.assignments.index', compact('assignments', 'filters', 'vehicles'));
    }

    /**
     * Yeni araç atama formunu gösterir. (Herkes erişebilir)
     */
    public function create()
    {

        $vehicles = Vehicle::where('is_active', true)->orderBy('plate_number')->get();
        if ($vehicles->isEmpty()) {
            return redirect()->route('service.vehicles.index')
                ->with('error', 'Atama yapılacak aktif araç bulunamadı. Lütfen önce araç ekleyin.');
        }
        return view('service.assignments.create', compact('vehicles'));
    }

    /**
     * Yeni araç atamasını veritabanında saklar (Bir Sonraki Seferi Bularak).
     * (Herkes erişebilir)
     */
    public function store(Request $request)
    {
        // Yetki kontrolü yok

        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'task_description' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'requester_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        // === ZAMAN DİLİMİ DÜZELTMESİ BAŞLANGICI ===

        $localTimezone = 'Europe/Istanbul'; // Tüm hesaplamalar bu saat dilimine göre yapılacak
        $schedules = ServiceSchedule::where('is_active', true)->orderBy('departure_time')->get();

        if ($schedules->isEmpty()) {
            return back()->withInput()->withErrors(['vehicle_id' => 'Sistemde tanımlı aktif bir sefer saati bulunamadı.']);
        }

        $now = Carbon::now($localTimezone); // Şu anki Istanbul saati (örn: 13:01)
        $targetDepartureTime = null;

        // 1. Bugünün seferlerini Istanbul saatine göre kontrol et
        foreach ($schedules as $schedule) {
            // Sefer saatini (örn: "13:00:00") bugünün tarihiyle Istanbul saat diliminde bir nesneye dönüştür
            $departureTime = Carbon::today($localTimezone)->setTimeFromTimeString($schedule->departure_time);
            // $departureTime = 30.10.2025 13:00:00 (Europe/Istanbul)

            // Kesim saatini Istanbul saatine göre hesapla
            $cutoffTime = $departureTime->copy()->subMinutes($schedule->cutoff_minutes);
            // $cutoffTime = 30.10.2025 12:30:00 (Europe/Istanbul)

            if ($now->lt($cutoffTime)) { // Şu anki saat (13:01) kesim saatinden (12:30) küçük mü? -> HAYIR
                $targetDepartureTime = $departureTime; // (Bu blok çalışmayacak)
                break;
            }
            // Döngü devam eder...
        }

        // 2. Bugün uygun sefer bulunamadıysa (saat 12:30'dan sonra), yarının ilk seferine ata
        if ($targetDepartureTime === null) {
            $firstSchedule = $schedules->first(); // 09:00:00 seferi
            // Yarının tarihini Istanbul saatine göre al ve sefer saatini ayarla
            $targetDepartureTime = Carbon::tomorrow($localTimezone)->setTimeFromTimeString($firstSchedule->departure_time);
            // $targetDepartureTime = 31.10.2025 09:00:00 (Europe/Istanbul)
        }

        $validatedData['user_id'] = Auth::id();
        $validatedData['start_time'] = $targetDepartureTime;
        $validatedData['end_time'] = $targetDepartureTime->copy()->addHour();
        VehicleAssignment::create($validatedData);

        // Başarı mesajında, saati tekrar lokal (Istanbul) formatında göster
        return redirect()->route('service.assignments.index')
            ->with('success', 'Araç görevi talebiniz başarıyla oluşturuldu (' . $targetDepartureTime->translatedFormat('d M H:i') . ' seferine eklendi).');
    }


    /**
     * Düzenleme formu (YETKİ GEREKLİ)
     */
    public function edit(VehicleAssignment $assignment)
    {
        $this->authorize('manage-assignment', $assignment);
        $vehicles = Vehicle::where('is_active', true)->orderBy('plate_number')->get();
        return view('service.assignments.edit', compact('assignment', 'vehicles'));
    }

    /**
     * Güncelleme (YETKİ GEREKLİ)
     */
    public function update(Request $request, VehicleAssignment $assignment)
    {
        $this->authorize('manage-assignment', $assignment);

        $validatedData = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'task_description' => 'required|string|max:255',
            'destination' => 'nullable|string|max:255',
            'requester_name' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $assignment->update($validatedData);

        return redirect()->route('service.assignments.index')
            ->with('success', 'Araç ataması başarıyla güncellendi.');
    }

    /**
     * Silme (YETKİ GEREKLİ)
     */
    public function destroy(VehicleAssignment $assignment)
    {
        if (Gate::denies('manage-assignment', $assignment)) {
            // Eğer 'manage-assignment' kuralı HAYIR derse (yetkisi yoksa)
            abort(403, 'Bu işlemi yapma yetkiniz bulunmamaktadır.');
        }

        // --- Eğer buraya geldiyse, kullanıcının yetkisi var demektir ---

        $assignment->delete();

        return redirect()->route('service.assignments.index') // Veya 'home'
            ->with('success', 'Araç görevi başarıyla silindi.');
    }
}
