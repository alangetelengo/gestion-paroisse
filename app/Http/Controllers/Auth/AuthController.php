<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Helpers\FlashAlert;
use App\Traits\LogsErrors;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use LogsErrors;

    /**
     * Affiche le formulaire de connexion
     */
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    /**
     * Traite la connexion
     */
    public function login(LoginRequest $request)
    {
        try {
            $credentials = $request->validated();
            $login = trim((string) $credentials['login']);
            $password = (string) $credentials['password'];
            $remember = $request->boolean('remember');

            $authenticated = false;

            if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
                $authenticated = Auth::attempt(['email' => $login, 'password' => $password], $remember);
            }

            if (! $authenticated) {
                $authenticated = Auth::attempt(['username' => $login, 'password' => $password], $remember);
            }

            if ($authenticated) {
                $request->session()->regenerate();

                $this->logInfo('Connexion réussie', ['user_id' => Auth::id()]);
                FlashAlert::success('Connexion réussie ! Bienvenue.');

                return redirect()->intended(route('dashboard'));
            }

            FlashAlert::error('Les identifiants fournis sont incorrects.');

            throw ValidationException::withMessages([
                'login' => 'Les identifiants fournis sont incorrects.',
            ]);
        } catch (ValidationException $e) {
            return back()->withErrors($e->errors())->withInput($request->only('login'));
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la connexion', $e, ['login' => $request->input('login')]);
            FlashAlert::error('Une erreur est survenue lors de la connexion.');
            return back()->withInput();
        }
    }

    /**
     * Déconnexion
     */
    public function logout(Request $request)
    {
        try {
            $userId = Auth::id();

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            $this->logInfo('Déconnexion réussie', ['user_id' => $userId]);
            FlashAlert::success('Vous avez été déconnecté avec succès.');

            return redirect()->route('login');
        } catch (\Exception $e) {
            $this->logError('Erreur lors de la déconnexion', $e);
            return redirect()->route('login');
        }
    }
}
