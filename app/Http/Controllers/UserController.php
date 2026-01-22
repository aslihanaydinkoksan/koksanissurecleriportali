<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Department;
use App\Models\BusinessUnit;
use Spatie\Permission\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * Kullanıcı Listesi
     */
    public function index()
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403);
        }

        // Eager Loading ile tüm ilişkileri tek sorguda çekiyoruz
        $users = User::with(['roles', 'departments', 'businessUnits'])
            ->latest()
            ->paginate(15);

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

        $roles = Role::all();
        $departments = Department::all();
        $businessUnits = BusinessUnit::where('is_active', true)->get();

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

        return DB::transaction(function () use ($request) {
            // 1. Kullanıcıyı oluştur
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            // 2. Rol, Departman ve Birim senkronizasyonu
            $user->assignRole($request->role);
            $user->departments()->sync($request->departments ?? []);
            $user->businessUnits()->sync($request->units ?? []);

            // 3. Veri bütünlüğünü sağla (Kritik!)
            $user->syncPrimaryBusinessUnit();

            return redirect()->route('users.index')->with('success', 'Kullanıcı ve yetkileri başarıyla tanımlandı.');
        });
    }

    /**
     * Kullanıcı Düzenleme Formu
     */
    public function edit(User $user)
    {
        if (!Auth::user()->can('manage_users')) {
            abort(403);
        }

        // Admin koruması: Admin olmayanlar admini düzenleyemez
        if ($user->hasRole('admin') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Yeterli yetkiniz yok.');
        }

        $roles = Role::all();
        $departments = Department::all();
        $businessUnits = BusinessUnit::where('is_active', true)->get();

        $userUnits = $user->businessUnits->pluck('id')->toArray();
        $userDepartments = $user->departments->pluck('id')->toArray();

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

        if ($user->hasRole('admin') && !Auth::user()->hasRole('admin')) {
            abort(403, 'Admin kullanıcısını düzenleyemezsiniz.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'exists:roles,name'],
            'departments' => ['nullable', 'array'],
            'units' => ['nullable', 'array'],
            'units.*' => ['exists:business_units,id'],
        ]);

        return DB::transaction(function () use ($request, $user) {
            // 1. Temel Bilgiler
            $userData = $request->only('name', 'email');
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $user->update($userData);

            // 2. Yetki (Sadece 3 rolden biri)
            $user->syncRoles([$request->role]);

            // 3. Departmanlar (Hangi modülleri göreceği)
            $user->departments()->sync($request->departments ?? []);

            // 4. Fabrikalar (Scope - Hangi verileri göreceği)
            $user->businessUnits()->sync($request->units ?? []);

            // 5. KRİTİK: Ana tablodaki business_unit_id'yi pivotun ilkiyle eşle
            $firstUnitId = $user->businessUnits()->first()?->id;
            $user->update(['business_unit_id' => $firstUnitId]);

            return redirect()->route('users.index')->with('success', 'Kullanıcı mimariye uygun olarak güncellendi.');
        });
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
            return back()->with('error', 'Kritik sistem yöneticileri silinemez.');
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'Kullanıcı silindi.');
    }
}