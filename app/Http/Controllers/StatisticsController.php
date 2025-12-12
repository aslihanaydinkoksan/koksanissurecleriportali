<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatisticsService;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\BusinessUnit;
use Carbon\Carbon;

class StatisticsController extends Controller
{
    protected $statsService;

    public function __construct(StatisticsService $statsService)
    {
        $this->middleware('auth');
        $this->statsService = $statsService;
    }

    /**
     * İstatistik Sayfası - Business Unit ve Spatie Entegrasyonu
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. KULLANICININ YETKİ SEVİYESİNİ BELİRLE (Spatie)
        // Admin, Yönetici veya Global yetkisi olanlar
        $isGlobalAdmin = $user->hasRole(['admin', 'yonetici']) || $user->can('view_all_business_units');

        // Müdür yetkisi (veya birden fazla departmana bakanlar)
        $isManager = !$isGlobalAdmin && ($user->hasRole('mudur') || $user->departments->count() > 1);

        // Görünüm Seviyesi
        $viewLevel = ($isGlobalAdmin || $isManager) ? 'full' : 'basic';

        // 2. ERİŞİLEBİLİR DEPARTMANLAR (Matris Yapı)
        $allowedDepartments = collect();

        if ($isGlobalAdmin) {
            $allowedDepartments = Department::orderBy('name')->get();
        } else {
            // Kullanıcının pivot tablodaki departmanlarını al
            $allowedDepartments = $user->departments;

            // Fallback: Eğer pivot boşsa ve eski usul department_id varsa onu ekle
            if ($allowedDepartments->isEmpty() && $user->department) {
                $allowedDepartments = collect([$user->department]);
            }
        }

        // 3. HEDEF DEPARTMANI BELİRLE VE DOĞRULA
        $requestedSlug = $request->input('target_dept');

        // Varsayılan slug belirleme
        if (!$requestedSlug) {
            if ($isGlobalAdmin) {
                $requestedSlug = 'genel';
            } else {
                $requestedSlug = $allowedDepartments->first()?->slug ?? 'genel';
            }
        }

        // Güvenlik: Yetkisiz departman erişimini engelle
        if (!$isGlobalAdmin && $requestedSlug !== 'genel' && !$allowedDepartments->contains('slug', $requestedSlug)) {
            $requestedSlug = $allowedDepartments->first()?->slug ?? 'genel';
        }

        // Standart çalışan "genel" bakışı göremez, kendi departmanına zorla
        if ($requestedSlug === 'genel' && !$isGlobalAdmin && !$isManager) {
            $requestedSlug = $allowedDepartments->first()?->slug;
        }

        $departmentSlug = $requestedSlug;

        // AKTİF FABRİKA BİLGİSİ (Başlık İçin)
        // Middleware tarafından session'a atılan veriyi alıyoruz
        $activeUnitName = session('active_unit_name', 'Tüm Fabrikalar');

        // Başlık Ayarı
        if ($departmentSlug === 'genel') {
            $departmentName = 'Genel Bakış';
            $pageTitle = "$activeUnitName - Yönetici Paneli";
        } else {
            $targetDeptObj = Department::where('slug', $departmentSlug)->first();
            $departmentName = $targetDeptObj ? $targetDeptObj->name : ucfirst($departmentSlug);
            $pageTitle = "$activeUnitName - $departmentName Raporu";
        }

        // Tarih Filtreleri
        $defaultStartDate = Carbon::now()->startOfYear();
        $defaultEndDate = Carbon::now()->endOfDay();
        $filters = [
            'date_from' => $request->input('date_from', $defaultStartDate->toDateString()),
            'date_to' => $request->input('date_to', $defaultEndDate->toDateString())
        ];
        $startDate = Carbon::parse($filters['date_from'])->startOfDay();
        $endDate = Carbon::parse($filters['date_to'])->endOfDay();

        // 4. VERİLERİ SERVİSTEN ÇEK
        // Servis içindeki sorgular '::forUser($user)' scope'unu kullanarak
        // otomatik olarak Coped/Preform ayrımını yapacak.

        $statsData = [];
        try {
            switch ($departmentSlug) {
                case 'lojistik':
                    $statsData = $this->statsService->getLojistikStatsData($startDate, $endDate, $viewLevel)->toArray();
                    break;
                case 'uretim':
                    $statsData = $this->statsService->getUretimStatsData($startDate, $endDate, $viewLevel)->toArray();
                    break;
                case 'hizmet':
                    $statsData = $this->statsService->getHizmetStatsData($startDate, $endDate, $viewLevel)->toArray();
                    break;
                case 'bakim':
                    $statsData = $this->statsService->getBakimStatsData($startDate, $endDate, $viewLevel)->toArray();
                    break;
                case 'ulastirma':
                    $statsData = $this->statsService->getUlastirmaStatsData($startDate, $endDate, $viewLevel)->toArray();
                    break;
                case 'genel':
                    $statsData = $this->statsService->getGenelBakisData($startDate, $endDate, $allowedDepartments)->toArray();
                    break;
                default:
                    $statsData = [];
                    break;
            }
        } catch (\Exception $e) {
            \Log::error('İstatistik Hatası: ' . $e->getMessage());
            $statsData = [];
        }

        $isTvUser = ($user->email === 'tv@koksan.com');

        return view('statistics.index', array_merge(
            [
                'pageTitle' => $pageTitle,
                'departmentSlug' => $departmentSlug,
                'departmentName' => $departmentName,
                'filters' => $filters,
                'isSuperUser' => $isGlobalAdmin,
                'isManager' => $isManager,
                'allowedDepartments' => $allowedDepartments,
                'viewLevel' => $viewLevel,
                'isTvUser' => $isTvUser,
                'activeUnitName' => $activeUnitName // View'de göstermek için
            ],
            $statsData
        ));
    }
}