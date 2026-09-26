<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForcePinChange
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->must_change_pin && ! $request->routeIs('pin.change', 'pin.update', 'logout')) {
            return redirect()->route('pin.change');
        }

        return $next($request);
    }
}
