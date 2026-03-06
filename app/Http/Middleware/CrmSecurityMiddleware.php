<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CrmSecurityMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 1. ROL KONTROLÜ: Sadece belirlenen roller CRM'e girebilir.
        // Kendi sistemindeki rollere göre burayı ('admin', 'yonetici', 'satis') güncelleyebilirsin.
        if (!$user || !$user->hasAnyRole(['admin', 'yonetici', 'satis_yoneticisi'])) {
            Log::warning("CRM İHLAL DENEMESİ (Yetkisiz Rol): Kullanıcı ID: {$user->id} - E-Posta: {$user->email} - IP: {$request->ip()}");
            abort(403, 'Bu modüle erişim yetkiniz bulunmamaktadır. Bu deneme sisteme loglandı.');
        }

        // 2. IP KISITLAMASI (IP Whitelisting)
        // .env dosyasından izin verilen IP'leri okuruz. '*' ise herkese açıktır.
        $allowedIps = env('CRM_ALLOWED_IPS', '*');

        if ($allowedIps !== '*') {
            $ips = explode(',', $allowedIps);
            $ips = array_map('trim', $ips); // Boşlukları temizle

            if (!in_array($request->ip(), $ips)) {
                Log::alert("CRM İHLAL DENEMESİ (Dış IP): Kullanıcı ID: {$user->id} - IP: {$request->ip()}");
                abort(403, 'Müşteri veri tabanına sadece şirket içi ağdan (veya VPN ile) erişilebilir.');
            }
        }

        return $next($request);
    }
}
