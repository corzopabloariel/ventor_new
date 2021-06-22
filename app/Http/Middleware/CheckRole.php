<?php

namespace App\Http\Middleware;
use Illuminate\Auth\Access\AuthorizationException;
use Closure;

class CheckRole
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle($request, Closure $next, $role)
    {
        if (!$request->user()->isAdmin() && empty($request->user()->permissions)) {
            throw new AuthorizationException("No tiene permiso para acceder a esta parte");
        }
        return $next($request);
    }

}