<?php

namespace App\Http\Controllers;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Models\CustomerMachine;
use App\Models\Complaint;
use App\Models\TestResult;
use App\Models\CustomerVisit;
use App\Models\CustomerActivity;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CustomerContact;
use App\Models\CustomerReturn;
use App\Models\Birim;

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
            // Ana firma email/telefonu opsiyonel bırakılabilir, artık kişilerde tutacağız
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',

            // Dinamik İletişim Kişileri Validasyonu
            'contacts' => 'nullable|array',
            'contacts.*.name' => 'required|string|max:255',
            'contacts.*.title' => 'nullable|string|max:100',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
        ]);

        try {
            DB::beginTransaction();

            // Müşteriyi oluştur (BusinessUnit trait'i otomatik doldurur)
            $customer = Customer::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'] ?? null,
                'phone' => $validatedData['phone'] ?? null,
                'address' => $validatedData['address'] ?? null,
                // contact_person sütununu artık kullanmayabiliriz veya birincil kişinin adını buraya yazabiliriz.
                // Şimdilik boş bırakıyoruz veya tablodan kaldırmayı düşünebiliriz.
            ]);

            // İletişim Kişilerini Kaydet
            if ($request->has('contacts')) {
                foreach ($request->contacts as $index => $contactData) {
                    $customer->contacts()->create([
                        'name' => $contactData['name'],
                        'title' => $contactData['title'] ?? null,
                        'email' => $contactData['email'] ?? null,
                        'phone' => $contactData['phone'] ?? null,
                        'is_primary' => $index === 0 // İlk eklenen kişiyi ana sorumlu yapalım
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
            'contacts', // Yeni ilişki
            'returns.complaint' // Yeni ilişki
        ]);

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

            // Mevcut kişilerin güncellenmesi veya yeni eklenmesi
            'contacts' => 'nullable|array',
            'contacts.*.id' => 'nullable|integer', // ID varsa güncelle, yoksa ekle
            'contacts.*.name' => 'required_with:contacts|string|max:255',
            'contacts.*.title' => 'nullable|string|max:100',
            'contacts.*.email' => 'nullable|email|max:255',
            'contacts.*.phone' => 'nullable|string|max:20',
            'contacts.*.delete' => 'nullable|boolean', // Silinecek mi?
        ]);

        try {
            DB::beginTransaction();

            // Ana müşteri bilgisini güncelle
            $customer->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'phone' => $validatedData['phone'],
                'address' => $validatedData['address'],
            ]);

            // İletişim Kişilerini Senkronize Et
            if ($request->has('contacts')) {
                foreach ($request->contacts as $contactData) {
                    // Silme isteği varsa
                    if (isset($contactData['delete']) && $contactData['delete'] == 1 && isset($contactData['id'])) {
                        CustomerContact::destroy($contactData['id']);
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
            return redirect()->route('customers.show', $customer)->with('success', 'Müşteri bilgileri güncellendi!');

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
                ->with('error', "Müşteri silinirken bir teknik hata oluştu. Bağlı makineler veya aktif lojistik kayıtları silmeyi engelliyor olabilir.");
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
    // İade Kaydetme Metodu 
    public function storeReturn(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'product_name' => 'required|string',
            'complaint_id' => 'nullable|exists:complaints,id',
            'quantity' => 'required|numeric',
            'unit' => 'required|string|exists:birims,ad',
            'reason' => 'required|string',
            'return_date' => 'required|date',
        ]);

        $customer->returns()->create([
            'user_id' => Auth::id(),
            'business_unit_id' => session('business_unit_id') ?? 1, // Middleware'den gelmeli
            'product_name' => $validated['product_name'],
            'complaint_id' => $validated['complaint_id'],
            'quantity' => $validated['quantity'],
            'unit' => $validated['unit'],
            'reason' => $validated['reason'],
            'return_date' => $validated['return_date'],
            'status' => 'pending'
        ]);

        return back()->with('success', 'İade kaydı oluşturuldu.');
    }
}
