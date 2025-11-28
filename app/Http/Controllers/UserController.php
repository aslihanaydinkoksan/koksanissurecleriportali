<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Role;       // Eklendi
use App\Models\Department; // Eklendi
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'departments'])->latest()->paginate(10);
        return view('users.index', compact('users'));
    }
    public function create()
    {
        // Formdaki select kutularını doldurmak için tüm verileri çekiyoruz
        $roles = Role::all();
        $departments = Department::all();

        return view('users.create', compact('roles', 'departments'));
    }

    public function store(Request $request)
    {
        // 1. Validasyon
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users', 'ends_with:@koksan.com'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['exists:departments,id'],
        ]);
        $firstRoleID = $request->roles[0];
        $roleData = Role::find($firstRoleID);

        // Eğer rol bulunduysa slug'ını (örn: 'mudur'), bulunamazsa 'kullanıcı' yazsın.
        $legacyRoleName = $roleData ? $roleData->slug : 'kullanıcı';
        $legacyDepartmentId = null;
        if ($request->has('departments') && count($request->departments) > 0) {
            $legacyDepartmentId = $request->departments[0];
        }

        // 3. Kullanıcıyı Oluştur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $legacyRoleName, // <--- İŞTE EKSİK OLAN PARÇA BU
            'department_id' => $legacyDepartmentId
        ]);

        // 4. İlişkileri Ata (Pivot Tablolara Yaz)
        // attach() metodu veritabanındaki ara tablolara kayıt atar
        $user->roles()->attach($request->roles);

        if ($request->has('departments')) {
            $user->departments()->attach($request->departments);
        }

        return redirect()->route('users.create')->with('success', 'Kullanıcı ve yetkileri başarıyla oluşturuldu!');
    }

    public function edit(User $user)
    {
        // Güvenlik Kontrolü: Düzenlenmek istenen kişi Admin ise, düzenleyen de Admin olmalı
        if ($user->hasRole('admin') && !Auth::user()->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Admin kullanıcıları sadece başka bir Admin tarafından düzenlenebilir.');
        }

        $roles = Role::all();
        $departments = Department::all();

        return view('users.edit', compact('user', 'roles', 'departments'));
    }

    public function update(Request $request, User $user)
    {
        // Güvenlik Kontrolü
        if ($user->hasRole('admin') && !Auth::user()->hasRole('admin')) {
            return redirect()->route('home')->with('error', 'Admin kullanıcıları sadece başka bir Admin tarafından düzenlenebilir.');
        }

        // Validasyon
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'roles' => ['required', 'array'],
            'roles.*' => ['exists:roles,id'],
            'departments' => ['nullable', 'array'],
            'departments.*' => ['exists:departments,id'],
        ]);

        // Güvenlik: Admin atama kontrolü (Update için)
        $adminRole = Role::where('slug', 'admin')->first();
        if ($adminRole && in_array($adminRole->id, $request->roles)) {
            if (!Auth::user()->hasRole('admin')) {
                return redirect()->back()
                    ->withErrors(['roles' => 'Yönetici rolündeki kullanıcılar Admin yetkisi atayamaz.'])
                    ->withInput();
            }
        }

        // Kullanıcı verilerini güncelle
        $data = $request->only('name', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }
        $user->update($data);

        // 5. KRİTİK NOKTA: sync()
        // sync() eski rolleri siler, yeni seçilenleri ekler. Tam olarak güncelleme mantığıdır.
        $user->roles()->sync($request->roles);

        // Departmanlar için sync (eğer boş gelirse tüm departmanları siler)
        $user->departments()->sync($request->departments ?? []);

        return redirect()->route('home')->with('success', 'Kullanıcı bilgileri ve yetkileri güncellendi!');
    }

    // Profil güncelleme metodları aynı kalabilir, sadece rol kontrolü gerekirse hasRole ile değiştirilmeli.
    public function profileEdit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
        // Mevcut kodunuzu korudum
        /** @var \App\Models\User $user */
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
        return redirect()->route('profile.edit')->with('success', 'Profil bilgileriniz başarıyla güncellendi!');
    }

    public function destroy(User $user)
    {
        // Rol kontrolü string'den metoda döndü
        if (!Auth::user()->hasRole('admin')) {
            return redirect()->back()->with('error', 'Kullanıcı silme yetkiniz bulunmamaktadır.');
        }

        // Pivot tablolardaki 'onDelete cascade' sayesinde 
        // kullanıcı silinince rol ve departman ilişkileri otomatik silinir.
        $user->delete();

        return redirect()->route('home')->with('success', $user->name . ' adlı kullanıcı başarıyla silindi.');
    }
}