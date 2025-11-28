<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\StatisticsService;
use Illuminate\Support\Facades\Auth;
use App\Models\Department;
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
     * İstatistik Sayfası - Rol ve Yetki Bazlı Filtreleme
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        // 1. KULLANICININ YETKİ SEVİYESİNİ BELİRLE
        $isGlobalAdmin = $user->role === 'admin' || $user->role === 'yönetici' || $user->email === 'tv@koksan.com';

        $isManager = !$isGlobalAdmin && ($user->role === 'mudur' || ($user->departments && $user->departments->count() > 1));

        // Görünüm Seviyesi: 'full' (Admin/Müdür) veya 'basic' (Çalışan)
        $viewLevel = ($isGlobalAdmin || $isManager) ? 'full' : 'basic';

        // 2. ERİŞİLEBİLİR DEPARTMANLARI BELİRLE
        $allowedDepartments = collect();

        if ($isGlobalAdmin) {
            $allowedDepartments = Department::orderBy('name')->get();
        } elseif ($isManager) {
            // Müdür yetkili olduğu departmanları görür
            $allowedDepartments = $user->departments && $user->departments->count() > 0
                ? $user->departments
                : collect([$user->department])->filter();
        } else {
            // Standart kullanıcı sadece kendi departmanını görür
            $allowedDepartments = collect([$user->department])->filter();
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

        // Standart çalışan "genel" bakışı göremez
        if ($requestedSlug === 'genel' && !$isGlobalAdmin && !$isManager) {
            $requestedSlug = $allowedDepartments->first()?->slug;
        }

        $departmentSlug = $requestedSlug;

        // Başlık Ayarı
        if ($departmentSlug === 'genel') {
            $departmentName = 'Genel Bakış';
            $pageTitle = "Yönetici Kontrol Paneli";
        } else {
            $targetDeptObj = Department::where('slug', $departmentSlug)->first();
            $departmentName = $targetDeptObj ? $targetDeptObj->name : ucfirst($departmentSlug);
            $pageTitle = $departmentName . " Departman Raporu";
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
        $statsData = [];
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
                'isTvUser' => $isTvUser
            ],
            $statsData
        ));
    }
}