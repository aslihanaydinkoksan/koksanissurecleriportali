<?php

namespace App\Http\Controllers;

use App\Models\Location;
use App\Models\Resident;
use App\Models\Stay;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // 1. Toplam İstatistikler
        $stats = [
            'total_residents' => Resident::count(),
            'total_locations' => Location::whereIn('type', ['apartment', 'room'])->count(), // Sadece oda/daire
            'occupied_locations' => Stay::whereNull('check_out_date')->count(),
        ];

        // Boş oda sayısı
        $stats['empty_locations'] = $stats['total_locations'] - $stats['occupied_locations'];

        // Doluluk Oranı
        $stats['occupancy_rate'] = $stats['total_locations'] > 0
            ? round(($stats['occupied_locations'] / $stats['total_locations']) * 100)
            : 0;

        // 2. Son Hareketler (Log yerine son giriş yapanları gösterelim)
        $latestStays = Stay::with(['resident', 'location'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'latestStays'));
    }
}