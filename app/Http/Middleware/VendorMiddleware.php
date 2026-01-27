<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VendorMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'Please login to access vendor panel');
        }

        // Check if user has vendor role
        if (auth()->user()->role !== 'vendor') {
            abort(403, 'Unauthorized. You must be a vendor to access this area.');
        }

        // Check if vendor profile exists
        if (!auth()->user()->vendor) {
            return redirect()->route('home')->with('error', 'Vendor profile not found. Please contact support.');
        }

        return $next($request);
    }
}
