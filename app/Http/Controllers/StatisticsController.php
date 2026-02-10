<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatisticsService;
use App\Services\FinanceStatisticsService; // Yeni servis eklendi
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
use App\Models\BusinessUnit;
use App\Models\User; // User modelini ekledik
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

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
        /** @var User $user */
        $user = Auth::user();

        // 1. KULLANICININ YETKİ SEVİYESİNİ BELİRLE (Spatie)
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
            $allowedDepartments = $user->departments;

            // HATA DÜZELTME (PHP0416): Undefined property: User::$department
            // magic property yerine relationship kontrolü veya optional kullanımı
            if ($allowedDepartments->isEmpty() && isset($user->department)) {
                $allowedDepartments = collect([$user->department]);
            }
        }

        // 3. HEDEF DEPARTMANI BELİRLE VE DOĞRULA
        $requestedSlug = $request->input('target_dept');

        if (!$requestedSlug) {
            $requestedSlug = $isGlobalAdmin ? 'genel' : ($allowedDepartments->first()?->slug ?? 'genel');
        }

        if (!$isGlobalAdmin && $requestedSlug !== 'genel' && !$allowedDepartments->contains('slug', $requestedSlug)) {
            $requestedSlug = $allowedDepartments->first()?->slug ?? 'genel';
        }

        if ($requestedSlug === 'genel' && !$isGlobalAdmin && !$isManager) {
            $requestedSlug = $allowedDepartments->first()?->slug;
        }

        $departmentSlug = $requestedSlug;

        // AKTİF FABRİKA BİLGİSİ
        $activeUnitId = session('active_unit_id');
        $activeUnitName = session('active_unit_name', 'Tüm Fabrikalar');

        // Başlık Ayarı
        if ($departmentSlug === 'genel') {
            $departmentName = 'Genel Bakış';
            $pageTitle = "$activeUnitName - Yönetici Paneli";
        } else {
            $targetDeptObj = Department::where('slug', $departmentSlug)->first();
            // HATA DÜZELTME (PHP0416): Undefined property: Model::$name
            $departmentName = ($targetDeptObj instanceof Department) ? $targetDeptObj->name : ucfirst($departmentSlug);
            $pageTitle = "$activeUnitName - $departmentName Raporu";
        }

        // Tarih Filtreleri
        $filters = [
            'date_from' => $request->input('date_from', Carbon::now()->startOfYear()->toDateString()),
            'date_to' => $request->input('date_to', Carbon::now()->endOfYear()->toDateString())
        ];
        $startDate = Carbon::parse($filters['date_from'])->startOfDay();
        $endDate = Carbon::parse($filters['date_to'])->endOfDay();

        $statsData = [];
        try {
            switch ($departmentSlug) {
                case 'lojistik':
                    $statsData = $this->statsService->getLojistikStatsData($startDate, $endDate, $viewLevel, $activeUnitId)->toArray();
                    break;
                case 'uretim':
                    $statsData = $this->statsService->getUretimStatsData($startDate, $endDate, $viewLevel, $activeUnitId)->toArray();
                    break;
                case 'hizmet':
                    $statsData = $this->statsService->getHizmetStatsData($startDate, $endDate, $viewLevel, $activeUnitId)->toArray();
                    break;
                case 'bakim':
                    $statsData = $this->statsService->getBakimStatsData($startDate, $endDate, $viewLevel, $activeUnitId)->toArray();
                    break;
                case 'ulastirma':
                    $statsData = $this->statsService->getUlastirmaStatsData($startDate, $endDate, $viewLevel, $activeUnitId)->toArray();
                    break;
                case 'genel':
                    $statsData = $this->statsService->getGenelBakisData($startDate, $endDate, $allowedDepartments, $activeUnitId)->toArray();
                    break;
                default:
                    $statsData = [];
                    break;
            }
        } catch (\Exception $e) {
            Log::error('İstatistik Hatası: ' . $e->getMessage());
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
                'activeUnitName' => $activeUnitName
            ],
            $statsData
        ));
    }

    /**
     * Finansal Dashboard - Masrafların Analizi
     */
    public function financeDashboard(Request $request, FinanceStatisticsService $service)
    {
        // Sadece yönetici ve admin görebilmeli
        if (!Auth::user()->hasAnyRole(['admin', 'yonetici'])) {
            abort(403, 'Bu sayfayı görüntüleme yetkiniz yok.');
        }

        $filters = [
            'date_from' => $request->get('date_from', now()->startOfYear()->toDateString()),
            'date_to' => $request->get('date_to', now()->endOfYear()->toDateString()),
        ];

        $data = $service->getGeneralOverview($filters);

        return view('statistics.finance', compact('data', 'filters'));
    }
}