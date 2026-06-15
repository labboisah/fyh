<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePharmacyManager
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        if ($request->user()->hasAllRoles(['pharmacist', 'head_of_department'])) {
            return $next($request);
        }

        return response()->view('errors.403', [], 403);
    }
}
