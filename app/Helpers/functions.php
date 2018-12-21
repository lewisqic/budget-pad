<?php

/**
 * Kint s() and die()
 * @param $input
 */
function sd($input)
{
    s($input);
    die();
}

/**
 * custom redirect helper function
 * @param  string $route
 * @return redirect
 */
function redir($route) {
    if ( \Request::ajax() || \Request::wantsJson() ) {
        $data = [
            'success' => true,
            'route' => $route
        ];
        if ( \Session::has('notification.message') && \Session::has('notification.status') ) {
            $data['status'] = \Session::get('notification.status');
            $data['message'] = \Session::get('notification.message');
            \Session::forget('notification');
        }
        return response()->json($data);
    } else {
        if ( \Request::has('_redir') ) {
            $route = \Request::input('_redir');
        }
        return redirect($route);
    }
}

/**
 * custom function to check for permissions access
 * @param string $permission
 * @return boolean
 */
function has_access($permission) {
    $auth_user = \Auth::check();
    if ( $auth_user && (!$auth_user->roles->isEmpty() || !empty($auth_user->permissions)) ) {
        return \Auth::hasAccess($permission);
    }
    return true;
}

/**
 * add the ordinal suffix to a number
 * @param int $number
 * @return string
 */
function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}

/**
 * Custom function to throw our app exception
 */
function fail($message = '') {
    throw new \AppExcp($message);
}

/**
 * Check for valid json string
 * @param $string
 *
 * @return bool
 */
function is_json($string) {
    json_decode($string);
    return (json_last_error() == JSON_ERROR_NONE);
}

/**
 * Determine if logged in user has a permission
 * @param $permissions
 *
 * @return bool
 */
function has_permission($permission) {
    $user = \Auth::user();
    if ( $user->superuser ) {
        return true;
    }
    try {
        $permissions = app('permissions');
        return $permissions->contains('name', $permission);
    } catch ( \Exception $e ) {
        return false;
    }
}

/**
 * Get a route name based on the uri and method
 *
 * @param        $uri
 * @param string $method
 *
 * @return null
 */
function route_name($uri, $method = 'get')
{
    $name = null;
    foreach ( \Route::getRoutes() as $route ) {
        $uri_regex = preg_replace('/\{\w+\}/', '\d+', $route->uri());
        if ( empty($route->getName()) || preg_match('/^_/', $uri_regex) ) {
            continue;
        }
        $methods = $route->methods();
        if ( in_array('PUT', $methods) && in_array('PATCH', $methods) ) {
            unset($methods[array_search('PATCH', $methods)]);
        }
        if ( !empty($uri_regex) && preg_match('/^' . str_replace('/', '\/', $uri_regex) . '$/', $uri) && in_array(strtoupper($method), $methods) ) {
            $name = $route->getName();
            break;
        }
    }
    return $name;
}