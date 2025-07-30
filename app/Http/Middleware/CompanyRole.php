<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CompanyRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if ($user->company_id !== null) {
            $com_ids = explode(',', $user->company_id);
            foreach ($com_ids as $com_id) {
                if (!$user->isCompanyAdmin($com_id)) {
                    return redirect()->back()->with('error', 'You are not authorized to access this page.');
                }
            }

        }

        return $next($request);
    }
}
