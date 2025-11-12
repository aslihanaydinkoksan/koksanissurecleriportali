<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;
use App\Http\Controllers\Controller;

class ActivityLogController extends Controller
{
    /**
     * Tüm aktiviteleri listeler.
     */
    public function index()
    {
        // Logları 'activity_log' tablosundan çek.
        // En yeniden en eskiye doğru sırala ve sayfala (50'şer 50'şer).
        $activities = Activity::latest()->paginate(50);

        // Veriyi 'logs.index' view'ına gönder
        return view('logs.index', compact('activities'));
    }
}
