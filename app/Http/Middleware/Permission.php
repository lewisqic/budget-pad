<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class Permission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();
        $permission = $request->route()->getName();
        if ( !$user->superuser && !empty($permission) && !has_permission($permission) ) {
            $action = $request->method() == 'GET' ? 'view this page' : 'perform that action';
            abort(401, 'Insufficient permissions to ' . $action . '.');
        }
        return $next($request);
    }
}
