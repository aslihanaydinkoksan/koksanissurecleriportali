<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\CustomerMachine;
use App\Models\Complaint;
use App\Models\TestResult;
use App\Models\CustomerVisit;
use App\Models\CustomerActivity;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        // Arama terimini al
        $search = $request->input('search');

        // Müşterileri al, arama varsa filtrele
        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                // 'name' veya 'email' veya 'phone' sütunlarında ara
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->orderBy('name', 'asc') // İsime göre sırala
            ->paginate(15); // Sayfalama yap (sayfa başına 15)

        return view('customers.index', compact('customers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // 1. Validasyon (Doğrulama)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255|unique:customers,email', // 'customers' tablosunda unique (benzersiz) olmalı
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        // 2. Kaydetme
        $customer = Customer::create($validatedData);

        // 3. Yönlendirme
        // Müşteriyi kaydettikten sonra 'index'e değil, direkt o müşterinin detay sayfasına gidelim
        return redirect()->route('customers.show', $customer)
            ->with('success', 'Müşteri başarıyla oluşturuldu!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function show(Customer $customer)
    {
        // Müşteriyi ve ona bağlı TÜM verileri tek bir sorguyla (verimli) yükle
        $customer->load([
            'machines', // Müşterinin makineleri
            'complaints', // Müşterinin şikayetleri
            'testResults', // Müşterinin test sonuçları
            'visits.event', // Müşteri ziyaretleri VE bu ziyaretlerin bağlı olduğu Event'ler
            'visits.travel' // Müşteri ziyaretleri VE bağlı olduğu Seyahatler
        ]);

        return view('customers.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Customer $customer)
    {
        // 1. Validasyon (Doğrulama)
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'email',
                'max:255',
                // Bu müşterinin kendi email'i hariç, başkasında bu email var mı diye kontrol et
                Rule::unique('customers')->ignore($customer->id),
            ],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);
        $customer->update($validatedData);

        // 3. Yönlendirme
        return redirect()->route('customers.show', $customer)
            ->with('success', 'Müşteri bilgileri başarıyla güncellendi!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\Response
     */
    public function destroy(Customer $customer)
    {
        // Yetki kontrolü (gerekirse) eklenebilir
        // $this->authorize('delete', $customer);

        try {
            $customer->delete(); // Bu artık soft delete yapacak

            return redirect()->route('customers.index')
                ->with('success', "'{$customer->name}' isimli müşteri başarıyla silindi (arşivlendi).");
        } catch (\Exception $e) {

            // Eğer müşteriye bağlı (ve silinemeyen) kayıtlar varsa
            // ve bir foreign key hatası alınırsa burası çalışır.
            return redirect()->route('customers.show', $customer)
                ->with('error', 'Müşteri silinirken bir hata oluştu: ' . $e->getMessage());
        }
    }
    public function getMachinesJson(Customer $customer)
    {
        // Müşteriye ait sadece ID, model ve seri no'yu al
        $machines = $customer->machines()
            ->select('id', 'model', 'serial_number')
            ->get();

        // JSON olarak döndür
        return response()->json($machines);
    }
    public function storeActivity(Request $request, $customerId)
    {
        $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
            'activity_date' => 'required|date',
        ]);

        CustomerActivity::create([
            'customer_id' => $customerId,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'description' => $request->description,
            'activity_date' => $request->activity_date,
        ]);

        return back()->with('success', 'Aktivite başarıyla kaydedildi.');
    }
}
