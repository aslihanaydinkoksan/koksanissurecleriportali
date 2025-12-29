<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    /**
     * Mekanları listeler.
     * Eğer $parentId null ise en üsttekileri (Siteleri) getirir.
     * Doluysa, o id'nin çocuklarını (Bloklarını vs.) getirir.
     */
    public function index(Request $request, $parentId = null)
    {
        // Eğer bir klasörün (mekanın) içindeysek o mekanı bul
        $parentLocation = $parentId ? Location::findOrFail($parentId) : null;

        // Listeyi çek
        $locations = Location::where('parent_id', $parentId)
            ->withCount('currentStays')
            ->orderBy('name')
            ->paginate(10);

        // Breadcrumb (Ekmek kırıntısı) için üst soy ağacını çıkaralım
        // Örn: Beytepe > A Blok > Daire 5
        $breadcrumbs = [];
        if ($parentLocation) {
            $temp = $parentLocation;
            while ($temp) {
                array_unshift($breadcrumbs, $temp); // Başa ekle
                $temp = $temp->parent; // Bir üste çık
            }
        }

        return view('locations.index', compact('locations', 'parentLocation', 'breadcrumbs'));
    }

    /**
     * Yeni mekan ekleme formu
     */
    // LocationController.php içindeki create metodu

    public function create($parentId = null)
    {
        $parentLocation = null;
        $allowedTypes = [];

        if ($parentId) {
            $parentLocation = Location::findOrFail($parentId);

            // HİYERARŞİ MANTIĞI BURADA KURULUR
            switch ($parentLocation->type) {
                case 'site':
                case 'campus':
                    // Site veya Kampüs altına -> Blok, Bağımsız Daire veya Ortak Alan eklenebilir
                    $allowedTypes = [
                        'block' => 'Blok',
                        'apartment' => 'Bağımsız Daire / Konut',
                        'common_area' => 'Ortak Alan / Sosyal Tesis'
                    ];
                    break;

                case 'block':
                    // Blok altına -> Sadece Daire eklenebilir
                    $allowedTypes = [
                        'apartment' => 'Daire'
                    ];
                    break;

                default:
                    // Diğer durumlarda ekleme yapılamaz
                    $allowedTypes = [];
                    break;
            }
        } else {
            // En üst seviye (Parent yok) -> Site veya Kampüs
            $allowedTypes = [
                'site' => 'Site / Lojman Grubu',
                'campus' => 'Kampüs / Yerleşke'
            ];
        }

        return view('locations.create', compact('parentLocation', 'allowedTypes'));
    }

    /**
     * Kaydetme işlemi
     */
    public function store(Request $request)
    {
        // 1. Doğrulama
        $validated = $request->validate([
            'name' => 'required|max:255',
            'type' => 'required',
            'ownership' => 'required',
            'parent_id' => 'nullable|exists:locations,id',

            // YENİ: Adres ve Konum
            'address' => 'nullable|string',
            'map_link' => 'nullable|url|max:500',

            'landlord_name' => 'nullable|string|max:255',
            'landlord_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'capacity' => 'nullable|integer',

            // Abonelik Numaraları
            'subs_electric' => 'nullable|string|max:50',
            'subs_water' => 'nullable|string|max:50',
            'subs_gas' => 'nullable|string|max:50',
            'subs_internet' => 'nullable|string|max:50',

            // YENİ: Abonelik Sahipleri (Form input name'leri böyle olmalı)
            'subs_electric_holder' => 'nullable|string|max:255',
            'subs_water_holder' => 'nullable|string|max:255',
            'subs_gas_holder' => 'nullable|string|max:255',
            'subs_internet_holder' => 'nullable|string|max:255',
        ]);

        // 2. Mekanı Kaydet
        $location = Location::create([
            'name' => $request->name,
            'type' => $request->type,
            'ownership' => $request->ownership,
            'parent_id' => $request->parent_id,
            'address' => $request->address,   // EKLENDİ
            'map_link' => $request->map_link, // EKLENDİ
            'landlord_name' => $request->landlord_name,
            'landlord_phone' => $request->landlord_phone,
            'notes' => $request->notes,
            'capacity' => $request->capacity ?? 1,
        ]);

        // 3. Abonelikleri Kaydet
        // Kod tekrarını önlemek için döngüye aldık
        $types = ['electric', 'water', 'gas', 'internet'];

        foreach ($types as $type) {
            $subNo = $request->input("subs_{$type}");
            $holderName = $request->input("subs_{$type}_holder");

            // Eğer numara girildiyse kaydet
            if ($subNo) {
                $location->subscriptions()->create([
                    'type' => $type,
                    'subscriber_no' => $subNo,
                    'holder_name' => $holderName, // EKLENDİ
                    // 'meter_no' => ... (Formda meter_no inputu varsa buraya eklersin)
                ]);
            }
        }

        return redirect()->route('locations.index', $request->parent_id)
            ->with('success', 'Mekan ve abonelik bilgileri başarıyla kaydedildi.');
    }

    /**
     * Dairenin veya odanın detay kartını gösterir (Show Page)
     */
    public function show($id)
    {
        $location = Location::with(['subscriptions', 'parent', 'assets'])->findOrFail($id);
        $breadcrumbs = [];
        $temp = $location;
        while ($temp->parent) {
            array_unshift($breadcrumbs, $temp->parent);
            $temp = $temp->parent;
        }
        return view('locations.show', compact('location', 'breadcrumbs'));
    }

    // Silme işlemi (Soft Delete)
    public function destroy($id)
    {
        // Mekanı bulurken ilişkileri sayarak (withCount) çekiyoruz
        $location = Location::withCount(['children', 'currentStays', 'assets'])->findOrFail($id);

        // 1. KONTROL: Alt birim var mı? (Örn: Bloğu silmeye çalışıyor ama içinde daireler var)
        if ($location->children_count > 0) {
            return back()->with('error', 'Bu mekanın altında başka birimler (Daire/Oda) var. Önce onları silmelisiniz.');
        }

        // 2. KONTROL: İçeride insan var mı? (EN KRİTİK KISIM)
        if ($location->current_stays_count > 0) {
            // Konaklayan kişinin ismini bulalım ki mesajda gösterelim
            $personName = $location->currentStays->first()->resident->first_name;
            return back()->with('error', "Bu mekanda şu an $personName konaklıyor! Silmeden önce Çıkış (Check-out) işlemi yapmalısınız.");
        }

        // 3. KONTROL: Demirbaş var mı?
        if ($location->assets_count > 0) {
            return back()->with('error', 'Bu mekana tanımlı demirbaşlar var. Mekanı silmeden önce demirbaşları silin veya başka yere taşıyın.');
        }

        // Her şey temizse sil (Soft Delete)
        $location->delete();

        return redirect()->route('locations.index')
            ->with('success', 'Mekan kaydı başarıyla silindi.');
    }
    /**
     * Düzenleme Formunu Göster
     */
    public function edit($id)
    {
        $location = Location::with('subscriptions')->findOrFail($id);

        // Eğer bu mekan bir yerin altındaysa (örn: Daire -> Blok altında), üst birimi bul
        $parentLocation = $location->parent;

        return view('locations.edit', compact('location', 'parentLocation'));
    }

    /**
     * Güncelleme İşlemi
     */
    public function update(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        // 1. Doğrulama
        $request->validate([
            'name' => 'required|max:255',
            'type' => 'required',
            'ownership' => 'required',
            // YENİ
            'address' => 'nullable|string',
            'map_link' => 'nullable|url|max:500',

            'landlord_name' => 'nullable|string|max:255',
            'landlord_phone' => 'nullable|string|max:50',
            'notes' => 'nullable|string',
            'capacity' => 'nullable|integer',

            // Abonelikler
            'subs_electric' => 'nullable|string|max:50',
            'subs_water' => 'nullable|string|max:50',
            'subs_gas' => 'nullable|string|max:50',
            'subs_internet' => 'nullable|string|max:50',

            // Abonelik Sahipleri
            'subs_electric_holder' => 'nullable|string|max:255',
            'subs_water_holder' => 'nullable|string|max:255',
            'subs_gas_holder' => 'nullable|string|max:255',
            'subs_internet_holder' => 'nullable|string|max:255',
        ]);

        // 2. Mekanı Güncelle
        $location->update([
            'name' => $request->name,
            'type' => $request->type,
            'ownership' => $request->ownership,
            'address' => $request->address,   // EKLENDİ
            'map_link' => $request->map_link, // EKLENDİ
            'landlord_name' => $request->landlord_name,
            'landlord_phone' => $request->landlord_phone,
            'notes' => $request->notes,
            'capacity' => $request->capacity ?? 1,
        ]);

        // 3. Abonelikleri Güncelle
        // Yardımcı fonksiyonu kullanarak her türü güncelliyoruz
        $this->updateSubscription($location, 'electric', $request->subs_electric, $request->subs_electric_holder);
        $this->updateSubscription($location, 'water', $request->subs_water, $request->subs_water_holder);
        $this->updateSubscription($location, 'gas', $request->subs_gas, $request->subs_gas_holder);
        $this->updateSubscription($location, 'internet', $request->subs_internet, $request->subs_internet_holder);

        return redirect()->route('locations.show', $location->id)
            ->with('success', 'Mekan bilgileri güncellendi.');
    }

    /**
     * Yardımcı Fonksiyon: Abonelik varsa güncelle, yoksa oluştur, boş geldiyse sil.
     */
    private function updateSubscription($location, $type, $number, $holderName = null)
    {
        if ($number) {
            // Varsa güncelle veya oluştur (updateOrCreate)
            $location->subscriptions()->updateOrCreate(
                ['type' => $type], // Neye göre arayacak? (Location ID zaten var, Type'a göre)
                [
                    'subscriber_no' => $number,
                    'holder_name' => $holderName // YENİ: Sahip ismini de yazıyoruz
                ]
            );
        } else {
            // Eğer formdan numara silindiyse, veritabanından da silelim
            $location->subscriptions()->where('type', $type)->delete();
        }
    }
    /**
     * Mekana Usta Atama (Service Assignment)
     */
    public function assignService(Request $request, $locationId)
    {
        $request->validate([
            'contact_id' => 'required|exists:contacts,id',
            'service_type' => 'required|string',
        ]);

        $assignment = \App\Models\ServiceAssignment::withTrashed()
            ->where('location_id', $locationId)
            ->where('service_type', $request->service_type)
            ->first();

        if ($assignment) {
            $assignment->contact_id = $request->contact_id;
            $assignment->deleted_at = null; // Silinmişse geri getir (Restore)
            $assignment->save();
        } else {
            // Kayıt hiç yoksa sıfırdan oluştur
            \App\Models\ServiceAssignment::create([
                'location_id' => $locationId,
                'service_type' => $request->service_type,
                'contact_id' => $request->contact_id
            ]);
        }

        return back()->with('success', 'Sorumlu usta ataması yapıldı.');
    }

    /**
     * Atamayı Kaldır
     */
    public function removeAssignment($assignmentId)
    {
        // ServiceAssignment modelini kullanmak için en tepeye: use App\Models\ServiceAssignment; eklemeyi unutma
        \App\Models\ServiceAssignment::findOrFail($assignmentId)->delete();
        return back()->with('success', 'Atama kaldırıldı.');
    }
    /**
     * Yazdırılabilir Bilgi Fişi (Info Sheet)
     */
    public function print($id)
    {
        $location = Location::with(['subscriptions', 'assets', 'parent'])->findOrFail($id);

        // Şu an kalan misafiri bul
        $activeStay = $location->currentStays->first();

        // Sorumlu ustaları bul (Hepsini tek tek çekmek yerine kategorileri hazırlayalım)
        $contacts = [
            'Elektrik' => $location->getResponsibleContact('electric'),
            'Su / Tesisat' => $location->getResponsibleContact('water'),
            'Doğalgaz' => $location->getResponsibleContact('gas'),
            'İnternet' => $location->getResponsibleContact('internet'),
            'Çilingir' => $location->getResponsibleContact('locksmith'),
        ];

        return view('locations.print', compact('location', 'activeStay', 'contacts'));
    }
    /**
     * Modaldan Hızlı Abonelik Ekleme
     */
    public function addSubscription(Request $request, $id)
    {
        $location = Location::findOrFail($id);

        $request->validate([
            'new_sub_type' => 'required|string',
            'new_sub_no' => 'required|string|max:50',
        ]);

        // updateOrCreate: Varsa günceller, yoksa yeni oluşturur.
        $location->subscriptions()->updateOrCreate(
            ['type' => $request->new_sub_type],
            ['subscriber_no' => $request->new_sub_no]
        );

        return back()->with('success', 'Abonelik bilgisi başarıyla eklendi/güncellendi.');
    }
}