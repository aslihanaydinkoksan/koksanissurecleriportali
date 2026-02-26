<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
use App\Models\CustomerProduct;
use App\Http\Requests\CustomerRequest;
use App\Http\Requests\CustomerActivityRequest;
use App\Models\Competitor;
use App\Services\Dashboard\CustomerReportService;

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
     * @param  \App\Http\Requests\CustomerRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CustomerRequest $request): RedirectResponse
    {
        // 1. Validasyon (FormRequest tarafından otomatik yapıldı ve tertemiz geldi)
        $validatedData = $request->validated();

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
    public function show(Customer $customer, CustomerReportService $reportService): View
    {
        $customer->load([
            'machines',
            'complaints',
            'testResults',
            'visits.event',
            'visits.travel',
            'contacts',
            'samples',
            'returns.complaint',
            'opportunities.histories.user',
            'opportunities.user',
            'vehicleAssignments.histories.user',
            'products.competitor',
            'machines.product'
        ]);

        $chartData = $reportService->getDashboardData($customer);
        // Birim listesini çekiyoruz
        $birimler = Birim::orderBy('ad')->get();
        $competitors = Competitor::where('is_active', true)->orderBy('name')->get();
        return view('customers.show', compact('customer', 'birimler', 'competitors', 'chartData'));
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
     * @param  \App\Http\Requests\CustomerRequest  $request
     * @param  \App\Models\Customer  $customer
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CustomerRequest $request, Customer $customer): RedirectResponse
    {
        // Doğrulanmış veriyi al
        $validatedData = $request->validated();

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
     * Müşteri Aktivitesi Kaydetme (Refactored)
     */
    public function storeActivity(CustomerActivityRequest $request, $customerId)
    {
        CustomerActivity::create([
            'customer_id' => $customerId,
            'user_id' => Auth::id(),
            'type' => $request->type,
            'contact_persons' => $request->contact_persons, // Request sınıfı hazırladı
            'description' => $request->description,
            'activity_date' => $request->activity_date,
        ]);

        return back()->with('success', 'Aktivite başarıyla kaydedildi.');
    }

    /**
     * Müşteri Aktivitesi Güncelleme (Refactored)
     */
    public function updateActivity(CustomerActivityRequest $request, CustomerActivity $activity)
    {
        $activity->update([
            'type' => $request->type,
            'contact_persons' => $request->contact_persons, // Request sınıfı hazırladı
            'description' => $request->description,
            'activity_date' => $request->activity_date,
        ]);

        return back()->with('success', 'İletişim kaydı güncellendi.');
    }

    public function destroyActivity(CustomerActivity $activity)
    {
        $activity->delete();
        return back()->with('success', 'İletişim kaydı silindi.');
    }

    /**
     * İade Kaydetme Metodu
     */
    public function storeReturn(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'shipped_quantity' => 'required|numeric|min:0.01',
            'shipped_unit' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string|exists:birims,ad',
            'return_date' => 'required|date',
            'complaint_id' => 'nullable|exists:complaints,id',
            'customer_sample_id' => 'nullable|exists:customer_samples,id',
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
            'customer_sample_id' => $validated['customer_sample_id'] ?? null,
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
        $return = CustomerReturn::findOrFail($id);

        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed'
        ]);

        $return->update(['status' => $validated['status']]);

        return back()->with('success', 'İade durumu güncellendi.');
    }

    public function updateReturn(Request $request, CustomerReturn $return)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'shipped_quantity' => 'required|numeric|min:0.01',
            'shipped_unit' => 'required|string',
            'quantity' => 'required|numeric|min:0.01',
            'unit' => 'required|string',
            'return_date' => 'required|date',
            'complaint_id' => 'nullable|exists:complaints,id',
            'customer_sample_id' => 'nullable|exists:customer_samples,id',
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

    /**
     * Yeni Numune Kaydetme
     */
    public function storeSample(Request $request, Customer $customer)
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

        // Akıllı ürün bulma/oluşturma
        $productId = null;
        if (!empty($validated['product_name'])) {
            $product = $customer->products()->firstOrCreate(['name' => $validated['product_name']]);
            $productId = $product->id;
        }

        $customer->samples()->create([
            'user_id' => Auth::id(),
            'business_unit_id' => 1,
            'customer_product_id' => $productId,
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
            'feedback' => $request->feedback
        ]);

        return back()->with('success', 'Numune durumu güncellendi.');
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
            $product = CustomerProduct::firstOrCreate([
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

    public function storeProduct(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'annual_volume' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'supplier_type' => 'required|in:koksan,competitor',
            'competitor_id' => 'nullable|required_if:supplier_type,competitor|exists:competitors,id',
            'customer_contact_id' => 'nullable|exists:customer_contacts,id',
            'performance_notes' => 'nullable|string',
        ]);

        // Dinamik Teknik Özellikleri Birleştirme
        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key)) {
                    $specs[$key] = $request->spec_values[$index] ?? '';
                }
            }
        }
        $validated['technical_specs'] = empty($specs) ? null : $specs;

        $customer->products()->create($validated);
        return back()->with('success', 'Ürün / Rakip bilgisi başarıyla eklendi.');
    }

    public function updateProduct(Request $request, CustomerProduct $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'nullable|string|max:255',
            'annual_volume' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'supplier_type' => 'required|in:koksan,competitor',
            'competitor_id' => 'nullable|required_if:supplier_type,competitor|exists:competitors,id',
            'customer_contact_id' => 'nullable|exists:customer_contacts,id',
            'performance_notes' => 'nullable|string',
        ]);

        $specs = [];
        if ($request->has('spec_keys') && $request->has('spec_values')) {
            foreach ($request->spec_keys as $index => $key) {
                if (!empty($key)) {
                    $specs[$key] = $request->spec_values[$index] ?? '';
                }
            }
        }
        $validated['technical_specs'] = empty($specs) ? null : $specs;

        $product->update($validated);
        return back()->with('success', 'Ürün / Rakip bilgileri başarıyla güncellendi.');
    }

    public function destroyProduct($id)
    {
        CustomerProduct::destroy($id);
        return back()->with('success', 'Ürün grubu silindi.');
    }
}
