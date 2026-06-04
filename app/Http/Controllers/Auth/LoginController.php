<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

        // =========================================================================
    // OAUTH: GOOGLE
    // =========================================================================

    public function redirectToGoogle()
    {
        return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGoogleCallBack()
    {
        try {
            $googleUser = Socialite::driver('google')->user();

            $user = User::updateOrCreate(
                ['email' => $googleUser->getEmail()],
                [
                    'name'      => $googleUser->getName(),
                    'google_id' => $googleUser->getId(),
                    'password'  => bcrypt(uniqid()),
                ]
            );

            Auth::login($user, true);
            return redirect($this->redirectTo);

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error al iniciar sesión con Google');
        }
    }

    // =========================================================================
    // OAUTH: GITHUB
    // =========================================================================

    public function redirectToGithub()
    {
        return Socialite::driver('github')->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGithubCallBack()
    {
        try {
            $githubUser = Socialite::driver('github')->stateless()->user();

            $githubId = is_object($githubUser) && method_exists($githubUser, 'getId')
                        ? $githubUser->getId()
                        : ($githubUser['id'] ?? null);

            if (!$githubId && isset($githubUser->user['id'])) {
                $githubId = $githubUser->user['id'];
            }

            if (!$githubId) {
                throw new \Exception("No se pudo obtener el ID de GitHub.");
            }

            $dbUser = \DB::table('users')->where('github_id', $githubId)->first();

            if (!$dbUser) {
                $email = $githubUser->getEmail() ?? ($githubUser->getNickname() ?? uniqid()) . '@github.com';

                $user = User::updateOrCreate(
                    ['email' => $email],
                    [
                        'name'      => $githubUser->getName() ?? $githubUser->getNickname() ?? 'Usuario GitHub',
                        'github_id' => $githubId,
                        'password'  => bcrypt(uniqid()),
                    ]
                );
            } else {
                $user = User::find($dbUser->id);
                $user->update([
                    'name'  => $githubUser->getName() ?? $githubUser->getNickname() ?? $user->name,
                    'email' => $githubUser->getEmail() ?? $user->email,
                ]);
            }

            Auth::login($user, true);
            return redirect($this->redirectTo);

        } catch (\Exception $e) {
            return redirect('/login')->with('error', 'Error con GitHub: ' . $e->getMessage());
        }
    }
}
