<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\TestResult;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestResultController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'test_name' => 'required|string|max:255',
            'test_date' => 'required|date',
            'summary' => 'nullable|string',
            'test_files.*' => 'nullable|file|mimes:jpeg,jpg,png,pdf|max:10240'
        ]);

        $testResult = $customer->testResults()->create([
            'user_id' => Auth::id(),
            'test_name' => $validated['test_name'],
            'test_date' => $validated['test_date'],
            'summary' => $validated['summary'],
        ]);

        // Spatie MediaLibrary ile Dosyaları Ekle
        if ($request->hasFile('test_files')) {
            foreach ($request->file('test_files') as $file) {
                $testResult->addMedia($file)->toMediaCollection('test_reports');
            }
        }

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Yeni test sonucu başarıyla eklendi!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function show(TestResult $testResult)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function edit(TestResult $testResult)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TestResult $testResult)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\TestResult  $testResult
     * @return \Illuminate\Http\Response
     */
    public function destroy(TestResult $testResult)
    {
        //
    }
}
