<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * Handle successful authentication and redirect based on role
     */
    protected function authenticated(Request $request, $user)
    {
        // Admin redirect
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        }

        // Vendor redirect - check status
        if ($user->role === 'vendor') {
            // Check if vendor exists
            if (!$user->vendor) {
                auth()->logout();
                return redirect()->route('login')
                    ->withErrors(['email' => 'Vendor profile not found. Please contact support.']);
            }

            return redirect()->route('vendor.dashboard');
        }

        // Customer redirect to home
        return redirect()->route('home');
    }

    /**
     * Get the post logout redirect path
     */
    protected function loggedOut(Request $request)
    {
        return redirect()->route('home');
    }
}
