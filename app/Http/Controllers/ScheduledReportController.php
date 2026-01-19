<?php

namespace App\Http\Controllers;

use App\Models\ScheduledReport;
use App\Services\ReportRegistryService;
use Illuminate\Http\Request;

class ScheduledReportController extends Controller
{
    /**
     * Tüm rapor planlarını listeler.
     */
    public function index()
    {
        $reports = ScheduledReport::orderBy('created_at', 'desc')->paginate(10);
        return view('admin.reports.index', compact('reports'));
    }

    public function create()
    {
        $reports = ReportRegistryService::getAvailableReports();
        $presets = ScheduledReport::getTimingPresets();
        return view('admin.reports.create', compact('reports', 'presets'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'report_name' => 'required|string|max:255',
            'report_class' => 'required|string',
            'frequency_preset' => 'required|string',
            'filter_frequency' => 'required|string|in:daily,weekly,monthly,last_3_months,last_6_months,yearly,minute',
            'recipients' => 'required|string',
            'file_format' => 'required|in:excel,pdf'
        ]);

        $presets = ScheduledReport::getTimingPresets();
        $selectedPreset = $presets[$request->frequency_preset] ?? null;

        if (!$selectedPreset) {
            return redirect()->back()->withErrors(['frequency_preset' => 'Geçersiz zamanlama seçimi.']);
        }

        ScheduledReport::create([
            'report_name' => $request->report_name,
            'report_class' => $request->report_class,
            'frequency' => $selectedPreset['frequency'],
            'filter_frequency' => $request->filter_frequency,
            'send_time' => $selectedPreset['time'],
            'file_format' => $request->file_format,
            'recipients' => array_map('trim', explode(',', $request->recipients)),
            'is_active' => true
        ]);

        // index'e yönlendiriyoruz
        return redirect()->route('report-settings.index')->with('success', 'Otomatik rapor planı başarıyla oluşturuldu!');
    }
    /**
     * Rapor düzenleme formunu gösterir.
     */
    public function edit(ScheduledReport $report)
    {
        $reports = ReportRegistryService::getAvailableReports();
        $presets = ScheduledReport::getTimingPresets();

        return view('admin.reports.edit', compact('report', 'reports', 'presets'));
    }

    /**
     * Mevcut rapor planını günceller.
     */
    public function update(Request $request, ScheduledReport $report)
    {
        $request->validate([
            'report_name' => 'required|string|max:255',
            'report_class' => 'required|string',
            'frequency_preset' => 'required|string',
            'filter_frequency' => 'required|string|in:daily,weekly,monthly,last_3_months,last_6_months,yearly,minute',
            'recipients' => 'required|string',
            'file_format' => 'required|in:excel,pdf'
        ]);

        $presets = ScheduledReport::getTimingPresets();
        $selectedPreset = $presets[$request->frequency_preset] ?? null;

        if (!$selectedPreset) {
            return redirect()->back()->withErrors(['frequency_preset' => 'Geçersiz zamanlama seçimi.']);
        }

        $report->update([
            'report_name' => $request->report_name,
            'report_class' => $request->report_class,
            'frequency' => $selectedPreset['frequency'],
            'filter_frequency' => $request->filter_frequency,
            'send_time' => $selectedPreset['time'],
            'file_format' => $request->file_format,
            // String olarak gelen mailleri temizleyip array'e çeviriyoruz
            'recipients' => array_map('trim', explode(',', $request->recipients)),
        ]);

        return redirect()->route('report-settings.index')->with('success', 'Rapor planı başarıyla güncellendi.');
    }

    /**
     * Raporu aktif/pasif yapar.
     */
    public function toggleStatus(ScheduledReport $report)
    {
        $report->update(['is_active' => !$report->is_active]);
        return back()->with('success', 'Durum güncellendi.');
    }

    /**
     * Planı siler.
     */
    public function destroy(ScheduledReport $report)
    {
        $report->delete();
        return back()->with('success', 'Rapor planı silindi.');
    }
}