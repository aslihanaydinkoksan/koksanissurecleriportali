<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (Throwable $e, $request) {

            // 1. Yetki Hatası (AuthorizationException)
            // 2. HTTP Erişim Reddi (AccessDeniedHttpException - Genelde 403 sayfası budur)
            if ($e instanceof AuthorizationException || $e instanceof AccessDeniedHttpException) {

                // Eğer kullanıcı "API" veya "AJAX" isteği atıyorsa JSON dön
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
                }

                // Normal tarayıcı isteği ise (Linke tıklama, form gönderme)
                // Kullanıcıyı geldiği sayfaya geri gönder ve hata mesajı bas
                return back()->with('error', 'Bu sayfaya veya işleme erişim yetkiniz bulunmamaktadır!');
            }
        });
    }
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Throwable  $e
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {
        // 1. Yetki Hatası (AuthorizationException) - Genelde $this->authorize() bunu fırlatır.
        // 2. Erişim Reddi (AccessDeniedHttpException) - Genelde Gate veya Middleware bunu fırlatır.
        // 3. Herhangi bir 403 Hatası (abort(403) vb.)

        if (
            $e instanceof AuthorizationException ||
            $e instanceof AccessDeniedHttpException ||
            ($e instanceof HttpException && $e->getStatusCode() == 403)
        ) {

            // Eğer istek AJAX (Takvimdeki sürükle bırak vb.) veya API isteği ise JSON dön
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['message' => 'Bu işlem için yetkiniz yok.'], 403);
            }

            // !!! İŞTE ÇÖZÜM BURASI !!!
            // Kullanıcıyı geldiği sayfaya (back) hata mesajıyla (flash message) geri gönder.
            // 403 Sayfası yerine geldiği ekranı görecek.
            return back()->with('error', 'Bu işlemi gerçekleştirmeye yetkiniz bulunmamaktadır!');
        }

        return parent::render($request, $e);
    }
}
