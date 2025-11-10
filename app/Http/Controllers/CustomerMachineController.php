<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\CustomerMachine;
use Illuminate\Http\Request;

class CustomerMachineController extends Controller
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
    public function store(Request $request, Customer $customer) // $customer'ı ekledik
    {
        $validated = $request->validate([
            'model' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255|unique:customer_machines,serial_number',
            'installation_date' => 'nullable|date',
        ]);

        // Müşteriye bağlı makineyi oluştur
        $customer->machines()->create($validated);

        return redirect()->route('customers.show', $customer)
            ->with('success', 'Yeni makine başarıyla eklendi!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\CustomerMachine  $customerMachine
     * @return \Illuminate\Http\Response
     */
    public function show(CustomerMachine $customerMachine)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\CustomerMachine  $customerMachine
     * @return \Illuminate\Http\Response
     */
    public function edit(CustomerMachine $customerMachine)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\CustomerMachine  $customerMachine
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CustomerMachine $customerMachine)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\CustomerMachine  $customerMachine
     * @return \Illuminate\Http\Response
     */
    public function destroy(CustomerMachine $customerMachine)
    {
        //
    }
}
