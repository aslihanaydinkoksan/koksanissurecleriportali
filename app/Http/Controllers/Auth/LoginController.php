<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use ReCaptcha\ReCaptcha;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    */

    use AuthenticatesUsers, ThrottlesLogins {
        ThrottlesLogins::incrementLoginAttempts insteadof AuthenticatesUsers;
        ThrottlesLogins::clearLoginAttempts insteadof AuthenticatesUsers;
        ThrottlesLogins::incrementLoginAttempts as protected traitIncrementLoginAttempts;
        ThrottlesLogins::clearLoginAttempts as protected traitClearLoginAttempts;
    }

    protected $maxAttempts = 5;
    protected $decayMinutes = 1;
    protected $captchaThreshold = 3;

    /**
     * Giriş yapıldıktan sonra kullanıcıların yönlendirileceği yer.
     */
    protected $redirectTo = '/welcome';

    /**
     * Yeni bir kontrolcü örneği oluşturur.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Login formunu gösterir.
     */
    public function showLoginForm(Request $request)
    {
        $key = 'login_ip_check|' . $request->ip();
        $attempts = Cache::get($key, 0);
        $showCaptcha = ($attempts >= $this->captchaThreshold);

        return view('auth.login', [
            'showCaptcha' => $showCaptcha
        ]);
    }

    /**
     * GİRİŞ İSTEĞİNİ DOĞRULAR (BİRLEŞTİRİLMİŞ METOD)
     * Hem KVKK'yı hem de ReCaptcha mantığını tek çatıda topladık.
     */
    protected function validateLogin(Request $request)
    {
        // 1. Temel Kurallar (KVKK Dahil)
        $rules = [
            $this->username() => 'required|string',
            'password' => 'required|string',
            'kvkk_approval' => 'required|accepted', // YENİ EKLENEN KURAL
        ];

        $messages = [
            'kvkk_approval.required' => 'Giriş yapabilmek için KVKK Aydınlatma Metnini onaylamanız gerekmektedir.',
            'kvkk_approval.accepted' => 'Giriş yapabilmek için KVKK Aydınlatma Metnini onaylamanız gerekmektedir.',
        ];

        // 2. Hatalı giriş sayısını kontrol et (ReCaptcha İçin)
        $key = 'login_ip_check|' . $request->ip();
        $attempts = Cache::get($key, 0);

        // 3. Eğer eşik aşılmışsa (3 ve üzeri), CAPTCHA kuralını ekle
        if ($attempts >= $this->captchaThreshold) {
            $rules['g-recaptcha-response'] = 'required';
            $messages['g-recaptcha-response.required'] = 'Lütfen robot olmadığınızı doğrulayın.';
        }

        // 4. Validasyonu Çalıştır
        $request->validate($rules, $messages);

        // 5. Eğer Captcha gerekliyse Google doğrulamasını yap
        if ($attempts >= $this->captchaThreshold) {
            $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));

            // Formdan gelen değeri ve IP adresini gönderiyoruz
            $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

            if (!$response->isSuccess()) {
                throw ValidationException::withMessages([
                    'g-recaptcha-response' => ['reCAPTCHA doğrulaması başarısız oldu. Lütfen sayfayı yenileyip tekrar deneyin.'],
                ]);
            }
        }
    }

    /**
     * Başarısız giriş denemesi sayacını artırır.
     */
    protected function incrementLoginAttempts(Request $request)
    {
        $this->traitIncrementLoginAttempts($request);
        $key = 'login_ip_check|' . $request->ip();
        $currentAttempts = Cache::get($key, 0);
        $currentAttempts++;
        Cache::put($key, $currentAttempts, $this->decayMinutes * 60);
    }

    /**
     * Başarılı girişten sonra denemeleri temizler.
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->traitClearLoginAttempts($request);
        $key = 'login_ip_check|' . $request->ip();
        Cache::forget($key);
    }

    /**
     * Hatalı giriş denemesinden sonra verilecek cevabı özelleştirir.
     */
    protected function sendFailedLoginResponse(Request $request)
    {
        $this->incrementLoginAttempts($request);
        $user = User::where($this->username(), $request->{$this->username()})->first();

        if ($user && !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'password' => [trans('auth.password')],
            ]);
        }

        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }

    /**
     * Kullanıcı başarıyla giriş yaptıktan sonra çalışır.
     */
    protected function authenticated(\Illuminate\Http\Request $request, $user)
    {
        // 1. Eğer kullanıcı TV ise direkt TV Dashboard'a gönder
        if ($user->email === 'tv@koksan.com') {
            return redirect()->route('tv.dashboard');
        }
        $intendedUrl = $request->session()->get('url.intended');

        if ($intendedUrl && str_contains($intendedUrl, 'notifications/check')) {
            // O adresi unut ve normal ana sayfaya git
            $request->session()->forget('url.intended');
            return redirect($this->redirectPath());
        }

        // 2. Diğer kullanıcılar normal akışına devam etsin
        return redirect()->intended($this->redirectPath());
    }
}