<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str; // Slug oluşturmak için gerekli
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
class RoleController extends Controller
{
    // GÜVENLİK DUVARI: Sadece Adminler Erişebilir
    public function __construct()
    {
        // 'auth' middleware'i giriş yapmış olmayı zorunlu kılar.
        // İçerideki checkAdmin fonksiyonu ise admin rolünü kontrol eder.
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->hasRole('admin')) {
                // Admin değilse ana sayfaya at ve hata mesajı göster
                return redirect()->route('home')->with('error', 'Bu sayfaya erişim yetkiniz yok.');
            }
            return $next($request);
        });
    }

    public function index()
    {
        $roles = Role::all();
        return view('roles.index', compact('roles'));
    }

    public function create()
    {
        return view('roles.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        // İsmi slug'a çevir (Örn: "Süper Yönetici" -> "super-yonetici")
        $slug = Str::slug($request->name);

        Role::create([
            'name' => $request->name,
            'slug' => $slug
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol başarıyla oluşturuldu.');
    }

    public function edit(Role $role)
    {
        // Temel rollerin (Admin) düzenlenmesini engellemek isterseniz buraya kontrol ekleyebilirsiniz.
        if ($role->slug === 'admin') {
            return redirect()->back()->with('error', 'Admin rolü düzenlenemez.');
        }

        return view('roles.edit', compact('role'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->slug === 'admin') {
            return redirect()->back()->with('error', 'Admin rolü düzenlenemez.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name)
        ]);

        return redirect()->route('roles.index')->with('success', 'Rol güncellendi.');
    }

    public function destroy(Role $role)
    {
        // Kritik Rolleri Silmeyi Engelle
        if (in_array($role->slug, ['admin', 'kullanici'])) {
            return redirect()->back()->with('error', 'Bu temel rol silinemez.');
        }

        // Rolü kullanan kullanıcı var mı kontrolü (İsteğe bağlı)
        if ($role->users()->count() > 0) {
            return redirect()->back()->with('error', 'Bu role sahip kullanıcılar var, önce onları değiştirin.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol silindi.');
    }
}