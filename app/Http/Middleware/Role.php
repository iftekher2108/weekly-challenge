<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Role
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Check if user's role matches any of the allowed roles
        if (!in_array($request->user()->role, $roles)) {
            return redirect()->back()->with('error', 'You are not authorized to access this page.');
        } else if ($request->user()->is_active == false) {
            return redirect()->back()->with('error', 'Your account is not active.');
        } else {
            return $next($request);
        }
    }
}
