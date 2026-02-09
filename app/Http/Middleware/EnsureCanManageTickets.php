<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCanManageTickets
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->hasPermissionTo('ticket.index') && $request->user()->hasPermissionTo('ticket.update')) {
            return $next($request);
        }

        return abort(Response::HTTP_FORBIDDEN);
    }
}
