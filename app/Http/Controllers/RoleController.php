<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str; // Slug oluşturmak için gerekli
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    // GÜVENLİK DUVARI: Sadece Admin ve Superadminler Erişebilir
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            if (!Auth::check() || !Auth::user()->hasAnyRole(['admin', 'superadmin'])) {
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
        $departments = \App\Models\Department::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        return view('roles.create', compact('departments', 'permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'department_id' => 'nullable|exists:departments,id',
            'permissions' => 'nullable|array'
        ]);

        $role = Role::create([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'department_id' => $request->department_id,
            'guard_name' => 'web' 
        ]);

        if ($request->has('permissions')) {
            $role->syncPermissions($request->permissions);
        }

        return redirect()->route('roles.index')->with('success', 'Rol ve yetkiler başarıyla oluşturuldu.');
    }

    public function edit(Role $role)
    {
        // Temel rollerin Düzenlenmesini Engelle
        if (in_array($role->slug, ['admin', 'superadmin'])) {
            return redirect()->back()->with('error', 'Temel roller (Admin/Superadmin) düzenlenemez.');
        }

        $departments = \App\Models\Department::orderBy('name')->get();
        $permissions = Permission::orderBy('name')->get();
        $rolePermissions = $role->permissions->pluck('name')->toArray();

        return view('roles.edit', compact('role', 'departments', 'permissions', 'rolePermissions'));
    }

    public function update(Request $request, Role $role)
    {
        if (in_array($role->slug, ['admin', 'superadmin'])) {
            return redirect()->back()->with('error', 'Temel roller düzenlenemez.');
        }

        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
            'department_id' => 'nullable|exists:departments,id',
            'permissions' => 'nullable|array'
        ]);

        $role->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
            'department_id' => $request->department_id 
        ]);

        $role->syncPermissions($request->permissions ?? []);

        return redirect()->route('roles.index')->with('success', 'Rol ve yetkiler güncellendi.');
    }

    public function destroy(Role $role)
    {
        // Kritik Rolleri Silmeyi Engelle
        if (in_array($role->slug, ['admin', 'superadmin'])) {
            return redirect()->back()->with('error', 'Bu temel rol silinemez.');
        }

        $role->delete();
        return redirect()->route('roles.index')->with('success', 'Rol silindi.');
    }
}
