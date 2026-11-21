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
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // RBAC Redirection Logic
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->hasRole('noc')) {
            // Future implementation: return redirect()->route('technician.dashboard');
            return redirect()->intended(route('dashboard', absolute: false));
        }

        if ($user->hasRole('Reseller')) {
            // Future implementation: return redirect()->route('reseller.dashboard');
            return redirect()->intended(route('dashboard', absolute: false));
        }

        \App\Models\AuditLog::log(
            'login',
            "User {$user->email} logged in",
            $user
        );

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        if (Auth::guard('web')->check()) {
            \App\Models\AuditLog::log(
                'logout',
                "User " . Auth::user()->email . " logged out",
                Auth::user()
            );
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
