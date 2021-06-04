<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            $role = "adm";
            if ($request->session()->has('role')) {
                $role = $request->session()->get('role');
                $request->session()->forget('role');
            }
            if (strpos($_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'], "/adm") === false) {
                return route('index', ['login' => 1]);
            }
            return route('index', ['login' => 1]);
        }
    }
}
