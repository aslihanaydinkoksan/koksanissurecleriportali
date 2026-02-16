<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Customer;
use App\Models\CustomerMachine;
use App\Models\Complaint;
use App\Models\TestResult;
use App\Models\CustomerVisit;
use App\Models\CustomerActivity;
use App\Models\CustomerContact;
use App\Models\CustomerReturn;
use App\Models\Birim;
use App\Models\CustomerSample;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return View
     */
    public function index(Request $request): View
    {
        // Arama terimini al
        $search = $request->input('search');

        // Müşterileri al, arama varsa filtrele
        $customers = Customer::query()
            ->when($search, function ($query, $search) {
                return $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('email', 'LIKE', "%{$search}%")
                    ->orWhere('phone', 'LIKE', "%{$search}%");
            })
            ->orderBy('name', 'asc')
            ->paginate(15);

        return view('customers.index', compact('customers', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return View
     */
    public function create(): View
    {
        return view('customers.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        // 1. Validasyon
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',

            // Dinamik İletişim Kişileri Validasyonu
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.title' => 'nullable|string|max:100',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        try {
            DB::beginTransaction();

            // Müşteriyi oluştur
            $customer = Customer::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
                'start_date' => $validatedData['start_date'] ?? null,
                'end_date' => $request->boolean('is_active') ? null : ($validatedData['end_date'] ?? null),
            ]);

            // İletişim Kişilerini Kaydet
            // DÜZELTME: $request->contacts yerine $validatedData['contacts'] kullanıldı
            if (!empty($validatedData['contacts'])) {
                foreach ($validatedData['contacts'] as $index => $contactData) {
                    // Boş satırları atla (isim yoksa kaydetme)
                    if (empty($contactData['name'])) continue;

                    $customer->contacts()->create([
                        'name' => $contactData['name'],
                        'title' => $contactData['title'] ?? null,
                        'email' => $contactData['email'] ?? null,
                        'phone' => $contactData['phone'] ?? null,
                        'is_primary' => $index === 0 // İlk eklenen kişiyi ana sorumlu yap
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('customers.show', $customer)
                ->with('success', 'Müşteri ve iletişim kişileri başarıyla oluşturuldu!');
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Müşteri oluşturma hatası: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Kayıt sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return View
     */
    public function show(Customer $customer): View
    {
        $customer->load([
            'machines',
            'complaints',
            'testResults',
            'visits.event',
            'visits.travel',
            'contacts',
            'samples',
            'returns.complaint'
        ]);

        // Birim listesini çekiyoruz
        $birimler = Birim::orderBy('ad')->get();

        return view('customers.show', compact('customer', 'birimler'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Contracts\View\View
     */
    public function edit(Customer $customer): View
    {
        return view('customers.edit', compact('customer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Customer $customer): RedirectResponse
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['nullable', 'email', Rule::unique('customers')->ignore($customer->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',

            'contacts' => 'nullable|array',
            'contacts.*.id' => 'nullable|integer',
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.title' => 'nullable|string|max:100',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'contacts.*.delete' => 'nullable|boolean',
        ]);

        try {
            DB::beginTransaction();

            // Ana müşteri bilgisini güncelle
            $customer->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                'is_active' => $request->has('is_active') ? $request->boolean('is_active') : true,
                'start_date' => $validatedData['start_date'] ?? null,
                'end_date' => $request->boolean('is_active') ? null : ($validatedData['end_date'] ?? null),
            ]);

            // İletişim Kişilerini Senkronize Et
            // DÜZELTME: $request->contacts yerine $validatedData['contacts'] kullanıldı
            if (!empty($validatedData['contacts'])) {
                foreach ($validatedData['contacts'] as $contactData) {

                    // Silme isteği varsa
                    if (isset($contactData['delete']) && $contactData['delete'] == 1) {
                        if (isset($contactData['id'])) {
                            CustomerContact::destroy($contactData['id']);
                        }
                        continue;
                    }

                    if (isset($contactData['id'])) {
                        // Güncelle
                        $contact = CustomerContact::find($contactData['id']);
                        if ($contact && $contact->customer_id == $customer->id) {
                            $contact->update($contactData);
                        }
                    } else {
                        // Yeni Ekle
                        if (!empty($contactData['name'])) {
                            $customer->contacts()->create($contactData);
                        }
                    }
                }
            }

            DB::commit();
            return redirect()->route('customers.show', $customer)
                ->with('success', 'Müşteri bilgileri başarıyla güncellendi!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->with('error', 'Hata: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Customer $customer): RedirectResponse
    {
        $customerName = $customer->name;

        try {
            $customer->delete();
            return redirect()->route('customers.index')
                ->with('success', "'{$customerName}' isimli müşteri kaydı silindi.");
        } catch (\Exception $e) {
            \Log::error("Müşteri Silme Hatası: " . $e->getMessage());
            return redirect()->route('customers.index')
                ->with('error', "Müşteri silinirken bir teknik hata oluştu.");
        }
    }

    public function getMachinesJson(Customer $customer)
    {
        $machines = $customer->machines()
            ->select('id', 'model', 'serial_number')
            ->get();

        return response()->json($machines);
    }

    /**
     * Müşteri Aktivitesi Kaydetme
     */
    public function storeActivity(Request $request, $customerId)
    {
        // DÜZELTME: Validasyon sonucunu değişkene atadık
        $validated = $request->validate([
            'type' => 'required|string',
            'contact_persons' => 'nullable|array', 
            'contact_persons.*' => 'string',
            'other_contact_persons' => 'nullable|string',
            'description' => 'required|string',
            'activity_date' => 'required|date',
        ]);
        $contacts = $validated['contact_persons'] ?? [];
        if (!empty($validated['other_contact_persons'])) {
            // Virgülle ayrılmış isimleri diziye çevir, boşlukları temizle ve boş olanları at
            $others = array_filter(array_map('trim', explode(',', $validated['other_contact_persons'])));
            $contacts = array_merge($contacts, $others);
        }
        // Aynı isim iki kez yazıldıysa tekilleştir
        $finalContacts = array_values(array_unique($contacts));

        CustomerActivity::create([
            'customer_id' => $customerId,
            'user_id' => Auth::id(),
            'type' => $validated['type'],
            'contact_persons' => $finalContacts,
            'description' => $validated['description'],
            'activity_date' => $validated['activity_date'],
        ]);

        return back()->with('success', 'Aktivite başarıyla kaydedildi.');
    }

    /**
     * İade Kaydetme Metodu
     */
    public function storeReturn(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'shipped_quantity' => 'required|numeric|min:0.01', // Gönderilen
            'shipped_unit' => 'required|string', // YENİ
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|exists:birims,ad',
            'return_date' => 'required|date',
            'complaint_id' => 'nullable|exists:complaints,id',
            'reason' => 'required|string',
        ]);

        $customer->returns()->create([
            'user_id' => Auth::id(),
            'business_unit_id' => 1, // Dinamik hale getirilmeli (Session/Auth user'dan)
            'product_name' => $validated['product_name'],
            'shipped_quantity' => $validated['shipped_quantity'],
            'shipped_unit' => $validated['shipped_unit'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'return_date' => $validated['return_date'],
            'complaint_id' => $validated['complaint_id'],
            'reason' => $validated['reason'],
            'status' => 'pending'
        ]);

        return back()->with('success', 'İade kaydı başarıyla oluşturuldu.');
    }

    /**
     * İade Durumunu Güncelleme
     */
    public function updateReturnStatus(Request $request, $id)
    {
        // DÜZELTME: Tam namespace yerine import edilen Class kullanımı
        $return = CustomerReturn::findOrFail($id);

        // DÜZELTME: Validasyon sonucunu değişkene atama
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        // DÜZELTME: $request->status yerine $validated['status'] kullanımı
        $return->update(['status' => $validated['status']]);

        return back()->with('success', 'İade durumu güncellendi.');
    }
    /**
     * Yeni Numune Kaydetme
     */
    public function storeSample(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255', // Değişti
            'subject' => 'required|string|max:255',
            'product_info' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'sent_date' => 'nullable|date',
            'cargo_company' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        // Akıllı ürün bulma/oluşturma
        $productId = null;
        if (!empty($validated['product_name'])) {
            $product = $customer->products()->firstOrCreate(['name' => $validated['product_name']]);
            $productId = $product->id;
        }

        $customer->samples()->create([
            'user_id' => Auth::id(),
            'business_unit_id' => 1,
            'customer_product_id' => $productId, // Yeni eklenen mantık
            'subject' => $validated['subject'],
            'product_info' => $validated['product_info'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'sent_date' => $validated['sent_date'] ?? now(),
            'cargo_company' => $validated['cargo_company'],
            'tracking_number' => $validated['tracking_number'],
            'status' => 'preparing'
        ]);

        return back()->with('success', 'Numune kaydı oluşturuldu.');
    }

    /**
     * Numune Durumunu Güncelleme
     */
    public function updateSampleStatus(Request $request, $id)
    {
        $sample = CustomerSample::findOrFail($id);

        $request->validate([
            'status' => 'required|in:preparing,sent,delivered,approved,rejected',
            'feedback' => 'nullable|string'
        ]);

        $sample->update([
            'status' => $request->status,
            'feedback' => $request->feedback // Durum değişirken not da eklenebilir
        ]);

        return back()->with('success', 'Numune durumu güncellendi.');
    }
    public function storeProduct(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'annual_volume' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);

        $customer->products()->create($validated);
        return back()->with('success', 'Ürün grubu başarıyla eklendi.');
    }

    public function destroyProduct($id)
    {
        \App\Models\CustomerProduct::destroy($id);
        return back()->with('success', 'Ürün grubu silindi.');
    }
    public function updateSample(Request $request, CustomerSample $sample)
    {
        $validated = $request->validate([
            'product_name' => 'nullable|string|max:255',
            'subject' => 'required|string|max:255',
            'product_info' => 'nullable|string|max:255',
            'quantity' => 'required|numeric|min:0',
            'unit' => 'required|string',
            'sent_date' => 'nullable|date',
            'cargo_company' => 'nullable|string|max:100',
            'tracking_number' => 'nullable|string|max:100',
        ]);

        $productId = null;
        if (!empty($validated['product_name'])) {
            $product = \App\Models\CustomerProduct::firstOrCreate([
                'customer_id' => $sample->customer_id,
                'name' => $validated['product_name']
            ]);
            $productId = $product->id;
        }

        $sample->update([
            'customer_product_id' => $productId,
            'subject' => $validated['subject'],
            'product_info' => $validated['product_info'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'sent_date' => $validated['sent_date'],
            'cargo_company' => $validated['cargo_company'],
            'tracking_number' => $validated['tracking_number'],
        ]);

        return back()->with('success', 'Numune başarıyla güncellendi.');
    }

    public function destroySample(CustomerSample $sample)
    {
        $sample->delete();
        return back()->with('success', 'Numune kaydı silindi.');
    }

    public function updateReturn(Request $request, CustomerReturn $return)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'shipped_quantity' => 'required|numeric|min:0.01', // Gönderilen
            'shipped_unit' => 'required|string', // YENİ
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string',
            'return_date' => 'required|date',
            'complaint_id' => 'nullable|exists:complaints,id',
            'reason' => 'required|string',
        ]);

        $return->update($validated);
        return back()->with('success', 'İade kaydı güncellendi.');
    }

    public function destroyReturn(CustomerReturn $return)
    {
        $return->delete();
        return back()->with('success', 'İade kaydı silindi.');
    }
    public function updateProduct(Request $request, \App\Models\CustomerProduct $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'annual_volume' => 'nullable|string|max:255',
            'notes' => 'nullable|string'
        ]);
        $product->update($validated);
        return back()->with('success', 'Ürün bilgileri başarıyla güncellendi.');
    }

    public function updateActivity(Request $request, CustomerActivity $activity)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'contact_persons' => 'nullable|array', 
            'contact_persons.*' => 'string',
            'other_contact_persons' => 'nullable|string',
            'description' => 'required|string',
            'activity_date' => 'required|date',
        ]);
        $contacts = $validated['contact_persons'] ?? [];
        if (!empty($validated['other_contact_persons'])) {
            $others = array_filter(array_map('trim', explode(',', $validated['other_contact_persons'])));
            $contacts = array_merge($contacts, $others);
        }
        $finalContacts = array_values(array_unique($contacts));

        $activity->update([
            'type' => $validated['type'],
            'contact_persons' => $finalContacts, 
            'description' => $validated['description'],
            'activity_date' => $validated['activity_date'],
        ]);
        return back()->with('success', 'İletişim kaydı güncellendi.');
    }

    public function destroyActivity(CustomerActivity $activity)
    {
        $activity->delete();
        return back()->with('success', 'İletişim kaydı silindi.');
    }
}
