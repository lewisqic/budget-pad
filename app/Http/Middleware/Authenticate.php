<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Facades\App\Services\UserService;

class Authenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $area = null)
    {
        if ( $area === null ) {
            fail('Invalid use of auth middleware');
        }
        $user = \Auth::user();
        if ( $user === null ) {
            \Session::put('url.intended', preg_replace('/^\//', '', $_SERVER['REQUEST_URI']));
            $redirect = 'auth/login';
        } else {
            $route = User::$types[$user->type]['route'];
            if ( $route != $area ) {
                $redirect = $route;
            }

            // store all permissions
            if ( $user->type == User::ADMINISTRATOR_ID ) {
                $user->guard_name = User::$types[User::ADMINISTRATOR_ID]['route'];
            } else if ( $user->type == User::MEMBER_ID ) {
                $user->guard_name = User::$types[User::MEMBER_ID]['route'];
            }
            if ( $user->custom_permissions ) {
                $permissions = \Auth::user()->getDirectPermissions();
            } else {
                $permissions = \Auth::user()->getPermissionsViaRoles();
            }
            app()->singleton('permissions', function($app) use ($permissions) {
                return $permissions;
            });

            UserService::shareUserData($user);
        }
        if ( isset($redirect) ) {
            if ( $request->ajax() || $request->wantsJson() ) {
                $data = ['success' => false, 'message' => 'Unauthorized', 'route' => $redirect];
                return response()->json($data, 401);
            } else {
                return redirect($redirect);
            }
        }
        return $next($request);
    }
}
