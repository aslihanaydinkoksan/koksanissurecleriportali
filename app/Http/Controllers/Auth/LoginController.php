<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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

    use AuthenticatesUsers;

    /**
     * Giriş yapıldıktan sonra kullanıcıların yönlendirileceği yer.
     *
     * @var string
     */
    protected $redirectTo = '/general-calendar';
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
        // 1. Girilen e-posta adresine sahip bir kullanıcı var mı diye kontrol et.
        $user = User::where($this->username(), $request->{$this->username()})->first();

        // 2. Eğer kullanıcı varsa ama girilen şifre yanlışsa...
        if ($user && !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                // Hata mesajı olarak 'password' alanına odaklan ve
                // lang/tr/auth.php dosyasındaki 'password' anahtarını kullan.
                'password' => [trans('auth.password')],
            ]);
        }

        // 3. Eğer kullanıcı hiç yoksa, varsayılan genel hatayı göster.
        // lang/tr/auth.php dosyasındaki 'failed' anahtarını kullan.
        throw ValidationException::withMessages([
            $this->username() => [trans('auth.failed')],
        ]);
    }
}
