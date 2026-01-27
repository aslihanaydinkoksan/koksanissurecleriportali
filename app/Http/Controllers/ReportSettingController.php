<?php

namespace App\Http\Controllers;

use App\Models\ReportSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportSettingController extends Controller
{
    public function index()
    {
        $settings = ReportSchedule::latest()->get();
        return view('report_settings.index', compact('settings'));
    }

    public function create()
    {
        return view('report_settings.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string',
            // Formdan gelen değerleri kabul edecek şekilde güncellendi:
            'frequency' => 'required|in:every_minute,daily_morning,daily_evening,weekly_monday,monthly_first',
            'data_scope' => 'required|in:last_24h,last_7d,last_30d,last_3m,last_6m,last_1y',
            'file_format' => 'required|in:excel,pdf',
            'recipients' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // Alıcıları diziye çeviriyoruz
        $validated['recipients'] = array_map('trim', explode(',', $request->recipients));
        // Checkbox kontrolü
        $validated['is_active'] = $request->has('is_active');

        ReportSchedule::create($validated);

        return redirect()->route('report-settings.index')
            ->with('success', 'Rapor planı başarıyla oluşturuldu.');
    }

    public function edit(ReportSchedule $reportSetting)
    {
        return view('report_settings.edit', compact('reportSetting'));
    }

    public function update(Request $request, ReportSchedule $reportSetting)
    {
        $validated = $request->validate([
            'report_name' => 'required|string|max:255',
            'report_type' => 'required|string',
            'frequency' => 'required|in:every_minute,daily_morning,daily_evening,weekly_monday,monthly_first',
            'data_scope' => 'required|in:last_24h,last_7d,last_30d,last_3m,last_6m,last_1y',
            'file_format' => 'required|in:excel,pdf',
            'recipients' => 'required|string',
            'is_active' => 'sometimes|boolean',
        ]);

        // Alıcıları temizleyip diziye çeviriyoruz
        $validated['recipients'] = array_map('trim', explode(',', $request->recipients));

        // Switch/Checkbox mantığı: Formda varsa true, yoksa false
        $validated['is_active'] = $request->has('is_active');

        $reportSetting->update($validated);

        return redirect()->route('report-settings.index')
            ->with('success', 'Rapor planı başarıyla güncellendi.');
    }

    public function destroy(ReportSchedule $reportSetting)
    {
        $reportSetting->delete();
        return redirect()->route('report-settings.index')
            ->with('success', 'Rapor planı silindi (Arşivlendi).');
    }
}