<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index()
    {
        // with('causer', 'subject') ekleyerek performansı artırıyoruz.
        // Böylece 50 log için 51 sorgu yerine 2 sorgu atar.
        $activities = Activity::with(['causer', 'subject'])
            ->latest()
            ->paginate(50);

        return view('logs.index', compact('activities'));
    }
}