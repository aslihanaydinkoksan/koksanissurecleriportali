<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Models\Department; // Bu satır sizde zaten vardı, harika.

class UserController extends Controller
{
    public function create()
    {
        $departments = Department::all(); // YENİ EKLENDİ
        return view('users.create', compact('departments')); // GÜNCELLENDİ
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'role' => ['required', Rule::in(['admin', 'yönetici', 'kullanıcı'])],
            'department_id' => [
                'nullable',
                'exists:departments,id',
                Rule::requiredIf($request->input('role') === 'kullanıcı')
            ],
        ]);

        if ($request->role === 'admin' && Auth::user()->role !== 'admin') {
            return redirect()->back()->withErrors(['role' => 'Yönetici rolündeki kullanıcılar Admin atayamaz.'])->withInput();
        }

        // --- GÜNCELLEME BURADA (Daha güvenli veri ataması) ---
        $data = $request->only('name', 'email', 'role');
        $data['password'] = Hash::make($request->password);

        // Rol 'kullanıcı' ise departmanı ata, değilse NULL ata
        if ($request->role === 'kullanıcı') {
            $data['department_id'] = $request->department_id;
        } else {
            $data['department_id'] = null; // Admin ve Yönetici için NULL
        }

        User::create($data);

        return redirect()->route('users.create')->with('success', 'Kullanıcı başarıyla oluşturuldu!');
    }

    public function edit(User $user)
    {
        if ($user->role === 'admin' && Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Admin kullanıcıları sadece başka bir Admin tarafından düzenlenebilir.');
        }

        $departments = Department::all(); // YENİ EKLENDİ
        return view('users.edit', compact('user', 'departments')); // GÜNCELLENDİ
    }

    public function update(Request $request, User $user)
    {
        if ($user->role === 'admin' && Auth::user()->role !== 'admin') {
            return redirect()->route('home')->with('error', 'Admin kullanıcıları sadece başka bir Admin tarafından düzenlenebilir.');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', Rule::in(['admin', 'yönetici', 'kullanıcı'])],
            'department_id' => [
                'nullable',
                'exists:departments,id',
                Rule::requiredIf($request->input('role') === 'kullanıcı')
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        if ($request->role === 'admin' && Auth::user()->role !== 'admin') {
            return redirect()->back()->withErrors(['role' => 'Yönetici rolündeki kullanıcılar Admin atayamaz.'])->withInput();
        }

        $data = $request->only('name', 'email', 'role');

        // Rol 'kullanıcı' ise departmanı ata, değilse NULL ata
        if ($request->role === 'kullanıcı') {
            $data['department_id'] = $request->department_id;
        } else {
            $data['department_id'] = null;
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('home')->with('success', 'Kullanıcı bilgileri başarıyla güncellendi!');
    }

    public function profileEdit()
    {
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

    public function profileUpdate(Request $request)
    {
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
}
