<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        // Önce isteği işle
        $response = $next($request);

        // Kullanıcı giriş yapmış mı kontrol et
        if (Auth::check()) {
            $user = Auth::user();

            // Loglanmayacak path'ler (Gereksiz DB şişkinliğini önlemek için)
            // Örn: Bildirim kontrolü, debugbar, resimler vb.
            $excludedPaths = [
                'debugbar',
                '_debugbar',
                'notifications/check',
                'livewire',
                'api/check-upcoming-events' // Örnek console command route'ları
            ];

            // Eğer istek AJAX değilse ve GET metoduysa (Sayfa görüntüleme)
            // Veya POST/PUT/DELETE işlemiyse ama DB loglaması dışında extra bilgi lazımsa
            if (!$request->ajax() && !$request->is($excludedPaths)) {

                $method = $request->getMethod();
                $url = $request->fullUrl();
                $ip = $request->ip();
                $agent = $request->userAgent();

                // Aktiviteyi kaydet
                // Not: 'system_access' log ismiyle kaydediyoruz ki diğerlerinden ayrılsın
                activity('system_access')
                    ->causedBy($user)
                    ->withProperties([
                        'ip' => $ip,
                        'agent' => $agent,
                        'method' => $method,
                        'url' => $url
                    ])
                    ->log("{$user->name}, {$url} sayfasını görüntüledi.");
            }
        }

        return $response;
    }
}
