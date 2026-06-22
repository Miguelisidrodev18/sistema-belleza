<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function showChangePassword(): View
    {
        return view('admin.profile.change-password');
    }

    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        auth()->user()->update([
            'password'             => $request->password,
            'must_change_password' => false,
        ]);

        return redirect()->route('admin.dashboard')
            ->with('success', 'Contraseña actualizada. Bienvenido/a.');
    }
}
