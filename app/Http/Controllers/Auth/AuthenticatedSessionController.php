<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Memproses autentikasi login pengguna.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Mengarahkan ke route 'dashboard' setelah berhasil login
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Memproses logout pengguna.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Mengarahkan langsung ke halaman login setelah logout
        return redirect()->route('login');
    }
}
