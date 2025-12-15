<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * Kullanıcı Listesi
     */
    public function index()
    {
        // Kendisi hariç diğer kullanıcıları listelesin mi? 
        // Hayır, hepsini görsün.
        $users = User::orderBy('name')->get();
        return view('users.index', compact('users'));
    }

    /**
     * Yeni Kullanıcı Formu
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Kaydetme
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed', // password_confirmation alanı ister
            'role' => 'required|string'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Şifreyi kriptola
            'role' => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'Yeni kullanıcı oluşturuldu.');
    }

    /**
     * Düzenleme Formu
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    /**
     * Güncelleme
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id, // Kendisi hariç unique
            'role' => 'required|string',
            'password' => 'nullable|string|min:6|confirmed', // Şifre boş bırakılabilir (değişmeyecekse)
        ]);

        // Temel bilgileri güncelle
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $request->role;

        // Eğer şifre kutusu doluysa şifreyi de güncelle
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('users.index')->with('success', 'Kullanıcı bilgileri güncellendi.');
    }

    /**
     * Silme
     */
    public function destroy($id)
    {
        if ($id == auth()->id()) {
            return back()->with('error', 'Kendinizi silemezsiniz!');
        }

        User::findOrFail($id)->delete();
        return redirect()->route('users.index')
            ->with('success', 'Kullanıcı kaydı başarıyla silindi.');
    }
}