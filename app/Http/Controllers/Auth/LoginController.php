<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
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
    protected $decayMinutes = 2;

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
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
        ]);


        $key = 'login_ip_check|' . $request->ip();
        $attempts = Cache::get($key, 0);

        if ($attempts >= $this->captchaThreshold) {
            $request->validate([
                'g-recaptcha-response' => 'required|recaptcha'
            ], [
                'g-recaptcha-response.required' => 'Lütfen robot olmadığınızı doğrulayın.',
                'g-recaptcha-response.recaptcha' => 'reCAPTCHA doğrulaması başarısız oldu, lütfen tekrar deneyin.',
            ]);
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
}
