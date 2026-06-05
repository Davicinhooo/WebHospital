<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;    
use Exception;

class SocialController extends Controller
{
    // ==========================================
    // SECCIÓN GITHUB
    // ==========================================

    public function redirectToGithub()
    {
        return Socialite::driver('github')->with(['prompt' => 'select_account'])->redirect();
    }

    // Manejo de la respuesta de GitHub (Callback)
    public function handleGithubCallback()
    {
        try {
            $githubUser = Socialite::driver('github')->user();
            
            // 1. Buscar si el usuario ya se había logueado con GitHub antes
            $user = User::where('github_id', $githubUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('home');
            }

            // 2. Si no tiene github_id, buscar por su correo por si ya tiene una cuenta tradicional o de Google
            $existingUser = User::where('email', $githubUser->email)->first();

            if ($existingUser) {
                // Vinculamos su ID de GitHub a la cuenta que ya existía
                $existingUser->update([
                    'github_id' => $githubUser->id
                ]);
                Auth::login($existingUser);
                return redirect()->route('home');
            }

            // 3. Si es un usuario completamente nuevo en el sistema, lo registramos
            $newUser = User::create([
                'name' => $githubUser->name ?? $githubUser->nickname,
                'email' => $githubUser->email,
                'github_id' => $githubUser->id,
                'password' => bcrypt('git_pass_random_123') // Contraseña aleatoria por seguridad
            ]);

            Auth::login($newUser);
            return redirect()->route('home');

        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'Ocurrió un error al intentar conectar con GitHub.');
        }
    }

    // ==========================================
    // SECCIÓN GOOGLE
    // ==========================================

    public function redirectToGoogle()
    {
    return Socialite::driver('google')->with(['prompt' => 'select_account'])->redirect();
    }

    public function handleGoogleCallback()
    {
    try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Buscar si el usuario ya se había logueado con Google antes
            $user = User::where('google_id', $googleUser->id)->first();

            if ($user) {
                Auth::login($user);
                return redirect()->route('home');
            }

            // 2. Si no tiene google_id, buscar por su correo por si ya tiene una cuenta tradicional o de Google
            $existingUser = User::where('email', $googleUser->email)->first();

            if ($existingUser) {
                // Vinculamos su ID de Google a la cuenta que ya existía
                $existingUser->update([
                    'google_id' => $googleUser->id
                ]);
                Auth::login($existingUser);
                return redirect()->route('home');
            }

            // 3. Si es un usuario completamente nuevo en el sistema, lo registramos
            $newUser = User::create([
                'name' => $googleUser->name ?? $googleUser->nickname,
                'email' => $googleUser->email,
                'google_id' => $googleUser->id,
                'password' => bcrypt('google_pass_random_123') // Contraseña aleatoria por seguridad
            ]);

            Auth::login($newUser);
            return redirect()->route('home');

        } catch (Exception $e) {
            // Comentamos la redirección por ahora
            // return redirect()->route('login')->with('error', 'Ocurrió un error...');
            
            // Forzamos a que imprima el error real en pantalla
            dd([
                'mensaje_de_error' => $e->getMessage(),
                'linea' => $e->getLine(),
                'archivo' => $e->getFile()
            ]);
        }
    }
}
