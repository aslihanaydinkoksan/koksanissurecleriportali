<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function index()
    {
        // En son yapılan işlemler en üstte
        $logs = \App\Models\SystemLog::with('user')->latest()->paginate(20);
        return view('logs.index', compact('logs'));
    }
}
