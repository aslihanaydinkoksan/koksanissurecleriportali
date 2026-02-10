<?php

namespace App\Http\Controllers\Approvals;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MaintenancePlan;
use Illuminate\Support\Facades\Auth;

class MaintenanceApprovalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Temel sorgu: Sadece onaya düşmüş olanlar
        $query = MaintenancePlan::with(['user', 'asset'])
            ->where('status', 'pending_approval');

        // EĞER ADMİN DEĞİLSE FİLTRELE
        if ($user->role !== 'admin') {

            // Yönetici ise sadece kendi departmanını görsün
            if ($user->isManagerOrDirector() && $user->department_id) {
                $query->whereHas('user', function ($q) use ($user) {
                    $q->where('department_id', $user->department_id);
                });
            } else {
                // Yetkisiz kullanıcı boş liste görür
                return view('approvals.maintenance', ['plans' => collect()]);
            }
        }

        $plans = $query->latest()->get();

        // Yeni bir view dosyası oluşturacağız: approvals/maintenance.blade.php
        return view('approvals.maintenance', compact('plans'));
    }
}
