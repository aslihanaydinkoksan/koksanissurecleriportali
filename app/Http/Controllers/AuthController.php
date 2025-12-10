<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Giriş formunu göster
    public function showLogin()
    {
        return view('auth.login');
    }

    // Giriş işlemini yap
    public function login(Request $request)
    {
        // 1. Doğrulama
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // 2. Giriş Denemesi
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate(); // Güvenlik için oturum ID yenile

            // Başarılıysa anasayfaya (dashboard) git
            return redirect()->intended('dashboard');
        }

        // 3. Başarısızsa geri dön
        return back()->withErrors([
            'email' => 'Girdiğiniz bilgiler sistem kayıtlarıyla uyuşmuyor.',
        ])->onlyInput('email');
    }

    // Çıkış Yap
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}