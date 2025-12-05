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
use App\Services\CsvExporter;
class MaintenanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    // 1. LİSTELEME EKRANI
    public function index(Request $request)
    {
        // 1. Sorguyu Başlat (İlişkilerle Beraber)
        $query = MaintenancePlan::with(['type', 'asset', 'user', 'timeEntries']);

        // 2. Filtreleme Mantığı

        // A. Metin Arama (Başlık veya Açıklamada)
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // B. Durum Filtresi (SelectBox)
        if ($request->filled('status')) {
            $query->where('status', $request->input('status'));
        }

        // C. Tarih Filtresi (Datepicker)
        if ($request->filled('date')) {
            $query->whereDate('planned_start_date', $request->input('date'));
        }

        // 3. Sıralama ve Sayfalama
        $plans = $query->orderBy('planned_start_date', 'desc')
            ->paginate(10) // Sayfada 10 kayıt göster
            ->appends(request()->query()); // Sayfa değişince filtreler kaybolmasın

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

        // 1. ÖN HAZIRLIK
        $oldStatus = $plan->status; // Eski durumu sakla (Karşılaştırma için şart)
        $data = $request->all();
        $user = Auth::user();
        $newStatus = $data['status'] ?? $oldStatus; // Formdan gelen yeni durum

        // 2. GÜVENLİK KONTROLLERİ

        // A) Geriye Dönüş Engeli (Onaydaki işi personel geri alamaz)
        if ($oldStatus == 'pending_approval' && in_array($newStatus, ['open', 'in_progress'])) {
            if ($user->cannot('approve', $plan)) {
                return back()
                    ->withInput()
                    ->with('error', 'HATA: Onaya sunulmuş bir planı geri çekemezsiniz. Yöneticinizin reddetmesi gerekmektedir.');
            }
        }

        // B) Süre Kontrolü (Hızlı Bitirme Notu YOKSA ve Süre de YOKSA engelle)
        // Eğer completion_note geliyorsa bu "Hızlı Bitirme" işlemidir, süre kontrolüne takılmasın.
        if (in_array($newStatus, ['pending_approval', 'completed']) && !$request->has('completion_note')) {
            $hasPastDuration = $plan->previous_duration_minutes > 0;
            // Aktif herhangi bir sayaç var mı?
            $isTimerRunning = $plan->timeEntries()->whereNull('end_time')->exists();

            if (!$hasPastDuration && !$isTimerRunning) {
                return back()
                    ->withInput()
                    ->with('error', 'HATA: Hiç çalışma kaydı bulunmayan bir iş onaya sunulamaz. Önce "Çalışmayı Başlat" deyiniz.');
            }
        }

        $request->validate([
            'title' => 'sometimes|required|string|max:255',
        ]);

        // 3. SENARYOLAR VE DURUM YÖNETİMİ

        // --- SENARYO 1: HIZLI BİTİRME (SAYAÇSIZ TAMAMLAMA) ---
        if ($request->has('completion_note')) {
            $data['completion_note'] = $request->completion_note;
            $data['actual_end_date'] = now();

            // Eğer personel tamamladıysa zorla onaya düşür
            if ($newStatus == 'completed' && $user->cannot('approve', $plan)) {
                $data['status'] = 'pending_approval';
                $newStatus = 'pending_approval'; // Aşağıdaki kontroller için güncel kalmalı
                session()->flash('success', 'İşlem tamamlandı ve Yönetici Onayına gönderildi.');
            }
        }

        // --- SENARYO 2: DURUM BİTİŞE/ONAYA GEÇİYORSA (SAYAÇ KAPATMA) ---
        if (in_array($newStatus, ['completed', 'pending_approval'])) {

            // Normal form güncellemesi ile geliyorsa da yetki kontrolü yap
            if ($newStatus == 'completed' && $user->cannot('approve', $plan)) {
                $data['status'] = 'pending_approval';
                $newStatus = 'pending_approval';
                session()->flash('success', 'Plan tamamlandı ve Yönetici Onayına gönderildi.');
            } else {
                if ($newStatus == 'completed') {
                    session()->flash('success', 'Plan başarıyla onaylandı ve tamamlandı.');
                }
            }

            // Varsa açık sayacı kapat (Sistemsel kapatma)
            $activeTimer = $plan->timeEntries()->whereNull('end_time')->first();
            if ($activeTimer) {
                $endTime = now();
                $activeTimer->update([
                    'end_time' => $endTime,
                    'duration_minutes' => $activeTimer->start_time->diffInMinutes($endTime),
                    'note' => $activeTimer->note ?? 'Durum değişikliği nedeniyle otomatik kapatıldı.'
                ]);
            }

            if ($newStatus == 'completed') {
                $data['actual_end_date'] = now();
            }
        }

        // --- SENARYO 3: DURUM "İŞLEMDE"YE GEÇİYORSA (SAYAÇ BAŞLATMA MANTIĞI) ---
        elseif ($newStatus === 'in_progress') {

            // KRİTİK KONTROL: Yönetici Reddi mi? Yoksa Personel Başlatması mı?

            // 1. Eğer eski durum 'pending_approval' ise bu bir REDDETME işlemidir. Sayaç BAŞLAMAMALI.
            $isRejection = ($oldStatus == 'pending_approval');

            // 2. Sadece reddetme DEĞİLSE ve kullanıcı planın SAHİBİ ise başlat.
            // (Yönetici reddettiğinde sayaç başlamayacak, kullanıcı sonra kendisi başlatacak)
            if (!$isRejection && $user->id === $plan->user_id) {

                // Kullanıcının açık sayacı yoksa başlat
                $hasActiveTimer = $plan->timeEntries()
                    ->where('user_id', $user->id)
                    ->whereNull('end_time')
                    ->exists();

                if (!$hasActiveTimer) {
                    $plan->timeEntries()->create([
                        'user_id' => $user->id,
                        'start_time' => now(),
                    ]);

                    if (is_null($plan->actual_start_date)) {
                        $data['actual_start_date'] = now();
                    }
                }
            }
        }

        // 4. VERİTABANI GÜNCELLEME VE LOGLAMA
        $plan->update($data);

        if ($oldStatus !== $plan->status) {

            // A) Reddetme Logu
            if ($oldStatus == 'pending_approval' && $plan->status == 'in_progress') {
                $this->logActivity($plan, 'rejected', "Plan yönetici tarafından REDDEDİLDİ ve personele geri gönderildi.");
            }
            // B) Normal Değişim Logu
            else {
                $labels = [
                    'open' => 'Açık',
                    'pending' => 'Bekliyor',
                    'in_progress' => 'İşlemde',
                    'pending_approval' => 'Onay Bekliyor',
                    'completed' => 'Tamamlandı',
                    'cancelled' => 'İptal'
                ];
                $oldText = $labels[$oldStatus] ?? $oldStatus;
                $newText = $labels[$plan->status] ?? $plan->status;

                $this->logActivity($plan, 'status_change', "Durum: {$oldText} -> {$newText} olarak güncellendi.");
            }
        }

        return redirect()->route('maintenance.show', $plan->id);
    }

    // 7. SİLME
    public function destroy($id)
    {
        $plan = MaintenancePlan::findOrFail($id);
        $this->authorize('delete', $plan);
        $this->logActivity($plan, 'deleted', 'Bakım planı silindi (Çöp kutusuna taşındı).');
        $plan->files()->delete();
        $plan->timeEntries()->delete();
        $plan->delete();

        return redirect()->route('maintenance.index')->with('success', 'Bakım planı başarıyla silindi.');
    }

    // --- ÖZEL METODLAR ---

    // SAYAÇ BAŞLAT
    public function startTimer($id)
    {
        $plan = MaintenancePlan::findOrFail($id);

        // Eğer plan "Tamamlandı" veya "Onay Bekliyor" ise sayaç başlatılamaz
        if (in_array($plan->status, ['completed', 'pending_approval'])) {
            return back()->with('error', 'Bu plan onaya sunulmuş veya tamamlanmıştır. Tekrar işlem yapılamaz.');
        }

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
        $user = Auth::user();

        // 1. Validasyon: Eğer iş bitiriliyorsa NOT girmek ZORUNLU olsun.
        if ($request->input('completion_type') === 'finish') {
            $request->validate([
                'note' => 'required|string|min:5', // En az 5 karakter açıklama şart
            ], [
                'note.required' => 'İşi bitirirken açıklama/sapma nedeni girmek zorunludur.',
                'note.min' => 'Lütfen açıklayıcı bir not giriniz.',
            ]);
        }

        // 2. Aktif sayacı bul ve kapat
        $entry = MaintenanceTimeEntry::where('maintenance_plan_id', $plan->id)
            ->where('user_id', $user->id)
            ->whereNull('end_time')
            ->first();

        if (!$entry) {
            return back()->with('error', 'Aktif bir sayacınız bulunamadı.');
        }

        $endTime = now();
        $duration = $entry->start_time->diffInMinutes($endTime);

        // Sayacı kapat ve süreyi kaydet
        $entry->update([
            'end_time' => $endTime,
            'duration_minutes' => $duration,
            'note' => $request->note // Kullanıcının o an girdiği not
        ]);

        // 3. Plan Durumunu ve Bitiş Notunu Güncelle
        $actionType = $request->input('completion_type', 'pause'); // Varsayılan pause
        $message = "Çalışma duraklatıldı.";

        if ($actionType === 'finish') {
            // --- İŞİ BİTİRME SENARYOSU ---

            // Güncellenecek ortak veriler
            $updateData = [
                'completion_note' => $request->note, // Sapma nedeni/Bitiş notu
                'actual_end_date' => now(),          // Gerçek bitiş zamanı
            ];

            // Yetki Kontrolü (Workflow)
            if ($user->cannot('approve', $plan)) {
                // Yetkisi YOKSA -> Onay Bekliyor
                $updateData['status'] = 'pending_approval';
                $message = "İş tamamlandı ve Yönetici Onayına gönderildi.";

                // Veritabanını güncelle
                $plan->update($updateData);

                // Log: Statü değişimi
                $this->logActivity($plan, 'status_change', 'Kullanıcı işi bitirdi, onay bekleniyor.');
            } else {
                // Yetkisi VARSA -> Tamamlandı
                $updateData['status'] = 'completed';
                $message = "İş başarıyla tamamlandı.";

                // Veritabanını güncelle
                $plan->update($updateData);

                // Log: Tamamlandı
                $this->logActivity($plan, 'completed', 'İşlem yetkili tarafından tamamlandı.');
            }

        } else {
            // --- SADECE DURAKLATMA SENARYOSU ---

            // Eğer plan durumu "pending" ise ve kullanıcı çalışıp durdurduysa "in_progress" kalmalı/olmalı.
            if ($plan->status == 'pending') {
                $plan->update(['status' => 'in_progress']);
            }

            // Log: Duraklatma
            $this->logActivity($plan, 'timer_paused', "Çalışmaya ara verildi. Not: {$request->note}");
        }

        return back()->with('success', $message);
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
    /**
     * 1. TOPLU LİSTE (exportList)
     */
    public function exportList()
    {
        return CsvExporter::streamDownload(
            query: MaintenancePlan::with(['type', 'asset', 'user'])->latest(),
            headers: ['ID', 'Başlık', 'Varlık', 'Durum', 'Planlanan Başlangıç', 'Sorumlu'],
            fileName: 'bakim-listesi-' . date('d-m-Y') . '.csv',
            rowMapper: function ($plan) {
                return [
                    $plan->id,
                    $plan->title,
                    $plan->asset ? $plan->asset->name : '-',
                    $plan->status_label, // Modeldeki accessor
                    $plan->planned_start_date ? $plan->planned_start_date->format('d.m.Y H:i') : '-',
                    $plan->user ? $plan->user->name : '-'
                ];
            }
        );
    }

    /**
     * 2. BAKIM İŞ EMRİ (export) - Tekil Fiş
     */
    public function export(MaintenancePlan $maintenancePlan)
    {
        $fileName = 'bakim-is-emri-' . $maintenancePlan->id . '.csv';

        $callback = function () use ($maintenancePlan) {
            $file = fopen('php://output', 'w');
            fputs($file, "\xEF\xBB\xBF");

            fputcsv($file, ['BAKIM İŞ EMRİ FORMU'], ';');
            fputcsv($file, [], ';');

            // Sol taraf Etiket, Sağ taraf Değer
            fputcsv($file, ['İş Emri No', $maintenancePlan->id], ';');
            fputcsv($file, ['Bakım Konusu', $maintenancePlan->title], ';');
            fputcsv($file, ['Varlık/Makine', $maintenancePlan->asset->name ?? '-'], ';');
            fputcsv($file, ['Bakım Türü', $maintenancePlan->type->name ?? '-'], ';');
            fputcsv($file, ['Öncelik', $maintenancePlan->priority_badge['text'] ?? '-'], ';');
            fputcsv($file, ['Durum', $maintenancePlan->status_label], ';');
            fputcsv($file, [], ';');

            fputcsv($file, ['--- ZAMANLAMA BİLGİLERİ ---'], ';');
            fputcsv($file, ['Planlanan Başlangıç', $maintenancePlan->planned_start_date?->format('d.m.Y H:i')], ';');
            fputcsv($file, ['Gerçekleşen Başlangıç', $maintenancePlan->actual_start_date?->format('d.m.Y H:i')], ';');
            fputcsv($file, ['Tamamlanma Tarihi', $maintenancePlan->actual_end_date?->format('d.m.Y H:i')], ';');
            fputcsv($file, [], ';');

            fputcsv($file, ['--- SONUÇ ---'], ';');
            fputcsv($file, ['Tamamlanma Notu', $maintenancePlan->completion_note ?? 'Not girilmemiş.'], ';');

            fclose($file);
        };

        return response()->stream($callback, 200, [
            "Content-type" => "text/csv; charset=utf-8",
            "Content-Disposition" => "attachment; filename=$fileName"
        ]);
    }
}