<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('pages.login');
    }

    public function login(LoginRequest $request): RedirectResponse
    {
        $login = trim($request->input('email'));
        $role  = $request->input('role');

        $user = User::where('email', $login)
            ->orWhere('dni', $login)
            ->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'Las credenciales no coinciden con nuestros registros.']);
        }

        if ($user->role !== $role) {
            Auth::logout();
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['role' => 'Tu cuenta no tiene el rol de ' . $role . '.']);
        }

        if (! $user->is_active) {
            Auth::logout();
            return back()
                ->withInput($request->only('email', 'role'))
                ->withErrors(['email' => 'Tu cuenta ha sido desactivada. Contacta al administrador.']);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return redirect()->intended(route($user->dashboardRoute()));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
