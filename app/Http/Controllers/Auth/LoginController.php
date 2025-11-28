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
    |
    | Bu kontrolcü, kullanıcıların uygulamaya giriş yapmasını yönetir ve
    | onları ana sayfaya yönlendirir.
    |
    */

    use AuthenticatesUsers, ThrottlesLogins {

        ThrottlesLogins::incrementLoginAttempts insteadof AuthenticatesUsers;

        ThrottlesLogins::clearLoginAttempts insteadof AuthenticatesUsers;

        ThrottlesLogins::incrementLoginAttempts as protected traitIncrementLoginAttempts;
        ThrottlesLogins::clearLoginAttempts as protected traitClearLoginAttempts;
    }

    protected $maxAttempts = 3;
    protected $decayMinutes = 1;

    protected $captchaThreshold = 3;

    /**
     * Giriş yapıldıktan sonra kullanıcıların yönlendirileceği yer.
     *
     * @var string
     */
    protected $redirectTo = '/welcome';
    //protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Yeni bir kontrolcü örneği oluşturur.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Hatalı giriş denemesinden sonra verilecek cevabı özelleştirir.
     * Bu metod, Laravel'in standart davranışını ezer.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Illuminate\Validation\ValidationException
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
     * Login formunu gösterir. (Override)
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
     * Başarısız giriş denemesi sayacını artırır. (Override)
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
     * Giriş isteğini doğrular. (Override)
     */
    protected function validateLogin(Request $request)
    {
        // 1. Standart kullanıcı adı ve şifre kontrolü
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);

        // 2. Hatalı giriş sayısını kontrol et
        $key = 'login_ip_check|' . $request->ip();
        $attempts = Cache::get($key, 0);

        // 3. Eğer eşik aşılmışsa (3 ve üzeri), CAPTCHA zorunlu olsun
        if ($attempts >= $this->captchaThreshold) {

            // A) Önce kutucuk işaretlenmiş mi diye bak (Validation)
            $request->validate([
                'g-recaptcha-response' => 'required'
            ], [
                'g-recaptcha-response.required' => 'Lütfen robot olmadığınızı doğrulayın.',
            ]);

            // B) İşaretlenmişse, Google'a sor: "Bu işaret gerçek mi?"
            $recaptcha = new ReCaptcha(config('services.recaptcha.secret'));

            // Formdan gelen değeri ve IP adresini gönderiyoruz
            $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

            // C) Google "Hayır, bu geçersiz" derse hatayı bas
            if (!$response->isSuccess()) {
                throw ValidationException::withMessages([
                    'g-recaptcha-response' => ['reCAPTCHA doğrulaması başarısız oldu. Lütfen sayfayı yenileyip tekrar deneyin.'],
                ]);
            }
        }
    }

    /**
     * Başarılı girişten sonra denemeleri temizler. (Override)
     */
    protected function clearLoginAttempts(Request $request)
    {
        $this->traitClearLoginAttempts($request);
        $key = 'login_ip_check|' . $request->ip();
        Cache::forget($key);
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

        // 2. Diğer kullanıcılar normal akışına (Home veya gitmek istedikleri yere) devam etsin
        return redirect()->intended($this->redirectPath());
    }
}
