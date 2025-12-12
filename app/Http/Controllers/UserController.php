<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\BusinessUnit; // EKLENDİ: Fabrikalar
use Spatie\Permission\Models\Role; // EKLENDİ: Spatie Rolleri
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Kullanıcı Listesi
     */
    public function index()
    {
        // Yetki Kontrolü
        if (!Auth::user()->can('manage_users')) {
            abort(403, 'Kullanıcıları görme yetkiniz yok.');
        }

        // Kullanıcıları, departmanlarını, rollerini ve BİRİMLERİNİ getir
        $users = User::with(['department', 'roles', 'businessUnits'])->latest()->paginate(10);
        return view('users.index', compact('users'));
    }

    /**
     * Yeni Kullanıcı Formu
     */
    public function create()
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403);
        }

        $roles = Role::all(); // Spatie Rolleri
        $departments = Department::all();
        $businessUnits = BusinessUnit::where('is_active', true)->get(); // Sadece aktif fabrikalar

        return view('users.create', compact('roles', 'departments', 'businessUnits'));
    }

    /**
     * Yeni Kullanıcı Kaydet
     */
    public function store(Request $request)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403);
        }

        // 1. Validasyon
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'ends_with:@koksan.com'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => ['required', 'exists:roles,name'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['exists:departments,id'],
            'units' => ['nullable', 'array'],
            'units.*' => ['exists:business_units,id'],
        ]);

        // 2. Kullanıcıyı Oluştur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            // Geriye dönük uyumluluk için ilk seçileni ana departman yapıyoruz
            'department_id' => $request->departments[0] ?? null,
            'role' => $request->role
        ]);

        // 3. Spatie Rol Ataması
        $user->assignRole($request->role);

        // 4. Business Unit (Fabrika) Ataması
        if ($request->has('units')) {
            $user->businessUnits()->attach($request->units);
        }

        // 5. DEPARTMAN ATAMASI (BU EKSİKTİ!) 🛠️
        // Çoklu departmanları pivot tabloya (department_user) kaydediyoruz.
        if ($request->has('departments')) {
            $user->departments()->attach($request->departments);
        }

        return redirect()->route('users.index')->with('success', 'Kullanıcı oluşturuldu.');
    }

    /**
     * Kullanıcı Düzenleme Formu
     */
    public function edit(User $user)
    {
        // ... yetki ve admin kontrolü

        $roles = Role::all();
        $departments = Department::all();
        $businessUnits = BusinessUnit::where('is_active', true)->get();

        // Birimler (Mevcut kodunuzdaki gibi)
        $userUnits = $user->businessUnits->pluck('id')->toArray();

        // 🛠️ BURADAKİ EKSİĞİ GİDERDİK 🛠️
        // Kullanıcının mevcut departman ID'lerini çekiyoruz
        $userDepartments = $user->departments->pluck('id')->toArray();

        // View'e $userDepartments değişkenini de gönderiyoruz
        return view('users.edit', compact('user', 'roles', 'departments', 'businessUnits', 'userUnits', 'userDepartments'));
    }

    /**
     * Kullanıcı Güncelle
     */
    public function update(Request $request, User $user)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403);
        }

        // Admin koruması
        if ($user->hasRole('admin') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Admin kullanıcısını düzenleyemezsiniz.');
        }

        // Validasyon
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'exists:roles,name'],
            'departments' => ['nullable', 'array'],
            'units' => ['nullable', 'array'],
            'units.*' => ['exists:business_units,id'],
        ]);

        // Temel Bilgileri Güncelle
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'department_id' => $request->departments[0] ?? null,
            'role' => $request->role, // Eski sütunu da güncelle
        ];

        // Şifre varsa güncelle
        if ($request->filled('password')) {
            $request->validate([
                'password' => ['confirmed', Rules\Password::defaults()],
            ]);
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        // 1. Rolü Senkronize Et (Eskisini siler, yenisini atar)
        $user->syncRoles([$request->role]);

        // 2. Birimleri Senkronize Et (Sync: Seçilmeyenleri siler, yenileri ekler)
        $user->businessUnits()->sync($request->units);

        // 3. Departmanları Senkronize Et
        // Formdan gelen 'departments' dizisini pivot tabloya eşitler.
        $user->departments()->sync($request->departments);

        return redirect()->route('users.index')->with('success', 'Kullanıcı bilgileri ve yetkileri güncellendi.');
    }

    /**
     * Profil Düzenleme (Kişisel)
     */
    public function profileEdit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    /**
     * Profil Güncelleme (Kişisel)
     */
    public function profileUpdate(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);

        return redirect()->route('profile.edit')->with('success', 'Profil bilgileriniz güncellendi.');
    }

    /**
     * Kullanıcı Sil
     */
    public function destroy(User $user)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403);
        }

        if ($user->hasRole('admin')) {
            return back()->with('error', 'Admin kullanıcısı silinemez.');
        }

        // Pivot tablolardaki ilişkiler (business_unit_user, model_has_roles)
        // veritabanındaki "ON DELETE CASCADE" ayarı sayesinde otomatik silinir.
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Kullanıcı silindi.');
    }
}