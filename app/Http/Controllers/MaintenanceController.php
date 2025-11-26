<?php

namespace App\Http\Controllers;

use App\Models\MaintenancePlan;
use App\Models\MaintenanceType;
use App\Models\MaintenanceAsset;
use App\Models\MaintenanceTimeEntry;
use App\Models\MaintenanceActivityLog;
use App\Models\MaintenanceFile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 1. LİSTELEME EKRANI
    public function index()
    {
        // İleride buraya filtreleme ekleyeceğiz (Tamamlananlar, Devam Edenler vs.)
        // Eager Loading (with) kullanarak N+1 sorununu önlüyoruz.
        $plans = MaintenancePlan::with(['type', 'asset', 'user', 'timeEntries'])
            ->orderBy('planned_start_date', 'desc')
            ->get();

        return view('maintenance.index', compact('plans'));
    }

    // 2. YENİ EKLEME FORMU
    public function create()
    {
        // Seeder ile eklediğimiz verileri buradan forma göndereceğiz
        $types = MaintenanceType::all();
        // Sadece aktif makineleri/zoneları getir, ayrıca kategoriye göre sırala
        $assets = MaintenanceAsset::where('is_active', true)
            ->orderBy('category')
            ->orderBy('name')
            ->get();

        return view('maintenance.create', compact('types', 'assets'));
    }

    // 3. KAYDETME İŞLEMİ
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'maintenance_type_id' => 'required|exists:maintenance_types,id',
            'maintenance_asset_id' => 'required|exists:maintenance_assets,id',
            'planned_start_date' => 'required|date',
            'planned_end_date' => 'required|date|after:planned_start_date',
            'priority' => 'required|in:low,normal,high,critical',
        ]);

        $plan = MaintenancePlan::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'description' => $request->description,
            'maintenance_type_id' => $request->maintenance_type_id,
            'maintenance_asset_id' => $request->maintenance_asset_id,
            'planned_start_date' => $request->planned_start_date,
            'planned_end_date' => $request->planned_end_date,
            'priority' => $request->priority,
            'status' => 'pending'
        ]);

        // LOGLAMA
        $this->logActivity($plan, 'created', 'Bakım planı oluşturuldu.');

        return redirect()->route('maintenance.index')->with('success', 'Bakım planı başarıyla oluşturuldu.');
    }

    // 4. DETAY GÖSTERME (Sayaç ve Dosyalar burada olacak)
    public function show($id)
    {
        $plan = MaintenancePlan::with(['type', 'asset', 'user', 'files', 'logs.user', 'timeEntries.user'])
            ->findOrFail($id);

        return view('maintenance.show', compact('plan'));
    }

    // 5. GÜNCELLEME FORMU
    public function edit($id)
    {
        $plan = MaintenancePlan::findOrFail($id);
        $types = MaintenanceType::all();
        $assets = MaintenanceAsset::where('is_active', true)->get();

        return view('maintenance.edit', compact('plan', 'types', 'assets'));
    }

    // 6. GÜNCELLEME İŞLEMİ
    public function update(Request $request, $id)
    {
        $plan = MaintenancePlan::findOrFail($id);
        $this->authorize('update', $plan);

        $request->validate([
            'title' => 'required|string|max:255',
            'status' => 'required',
        ]);

        $oldStatus = $plan->status;

        $plan->update($request->all());

        // Durum değiştiyse logla
        if ($oldStatus !== $request->status) {
            $this->logActivity($plan, 'status_change', "Durum $oldStatus -> {$request->status} olarak değiştirildi.");
        }

        return redirect()->route('maintenance.show', $plan->id)->with('success', 'Plan güncellendi.');
    }

    // 7. SİLME
    public function destroy($id)
    {
        // 1. Planı bul
        $plan = MaintenancePlan::findOrFail($id);

        // 2. LOGLAMA (En Başta)
        // Plan henüz silinmemişken logu atıyoruz. 
        // Böylece log kaydı oluşturulurken planın ID'sine ve diğer bilgilerine sorunsuz erişiyoruz.
        $this->logActivity($plan, 'deleted', 'Bakım planı silindi (Çöp kutusuna taşındı).');

        // 3. Bağlı verileri de Soft Delete yap (Zincirleme Temizlik)
        // Plan çöp kutusuna gidince dosyaları ve sayaçları da peşinden gitsin.
        $plan->files()->delete();
        $plan->timeEntries()->delete();

        // 4. Ana planı Soft Delete yap (deleted_at sütununu doldurur)
        $plan->delete();

        return redirect()->route('maintenance.index')->with('success', 'Bakım planı başarıyla silindi.');
    }

    // --- ÖZEL METODLAR ---

    // SAYAÇ BAŞLAT
    public function startTimer($id)
    {
        $plan = MaintenancePlan::findOrFail($id);

        // Kullanıcının bu planda açık bir sayacı var mı?
        $existingEntry = MaintenanceTimeEntry::where('maintenance_plan_id', $plan->id)
            ->where('user_id', Auth::id())
            ->whereNull('end_time')
            ->first();

        if ($existingEntry) {
            return back()->with('error', 'Zaten devam eden bir sayacınız var.');
        }

        // Eğer plan durumu "pending" ise otomatik "in_progress" yapalım
        if ($plan->status === 'pending') {
            $plan->update(['status' => 'in_progress', 'actual_start_date' => now()]);
        }

        MaintenanceTimeEntry::create([
            'maintenance_plan_id' => $plan->id,
            'user_id' => Auth::id(),
            'start_time' => now(),
        ]);

        $this->logActivity($plan, 'timer_started', 'Çalışma sayacı başlatıldı.');

        return back()->with('success', 'Çalışma başlatıldı.');
    }

    // SAYAÇ DURDUR
    public function stopTimer(Request $request, $id)
    {
        $plan = MaintenancePlan::findOrFail($id);

        $entry = MaintenanceTimeEntry::where('maintenance_plan_id', $plan->id)
            ->where('user_id', Auth::id())
            ->whereNull('end_time')
            ->first();

        if (!$entry) {
            return back()->with('error', 'Aktif bir sayacınız bulunamadı.');
        }

        $endTime = now();
        $duration = $entry->start_time->diffInMinutes($endTime);

        $entry->update([
            'end_time' => $endTime,
            'duration_minutes' => $duration,
            'note' => $request->note // Durdururken not girebilir
        ]);

        $this->logActivity($plan, 'timer_stopped', "Çalışma durduruldu. Süre: $duration dk. Not: {$request->note}");

        return back()->with('success', 'Çalışma durduruldu ve süre kaydedildi.');
    }

    // DOSYA YÜKLEME
    public function uploadFile(Request $request, $id)
    {
        // 1. Validasyonu güncelle: 'file' yerine 'files.*' (Dizideki her dosya için)
        $request->validate([
            'files' => 'required',
            'files.*' => 'file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx,pptx|max:51200',
        ]);

        $plan = MaintenancePlan::findOrFail($id);

        if ($request->hasFile('files')) {
            // Döngü ile gelen her dosyayı işle
            foreach ($request->file('files') as $file) {

                // İsim temizleme (Türkçe karakter düzeltme)
                $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                $extension = $file->getClientOriginalExtension();
                $safeName = \Illuminate\Support\Str::slug($originalName);
                $filename = time() . '_' . uniqid() . '_' . $safeName . '.' . $extension; // uniqid ekledik ki aynı saniyede yüklenenler çakışmasın

                // Kaydet
                $path = $file->storeAs('uploads/maintenance', $filename, 'public');

                MaintenanceFile::create([
                    'maintenance_plan_id' => $plan->id,
                    'file_path' => '/storage/' . $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $extension,
                ]);
            }

            // Log kaydını bir kere atalım (veya döngü içinde tek tek atılabilir)
            $count = count($request->file('files'));
            $this->logActivity($plan, 'file_upload', "$count adet yeni dosya yüklendi.");
        }

        return back()->with('success', 'Dosya(lar) başarıyla yüklendi.');
    }

    // DOSYA SİLME
    public function deleteFile($file_id)
    {
        $file = MaintenanceFile::findOrFail($file_id);
        $plan = $file->plan;
        $fileName = $file->file_name;

        // Bu komut artık satırı silmeyecek, sadece 'deleted_at' sütununa tarih basacak.
        $file->delete();

        $this->logActivity($plan, 'file_deleted', 'Dosya çöp kutusuna taşındı: ' . $fileName);

        return back()->with('success', 'Dosya başarıyla silindi.');
    }

    // YARDIMCI: LOGLAMA FONKSİYONU
    private function logActivity($plan, $action, $description)
    {
        MaintenanceActivityLog::create([
            'maintenance_plan_id' => $plan->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'description' => $description
        ]);
    }
}