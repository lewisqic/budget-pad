<?php

namespace App\Helpers;

class Js {


    /**
     * return javascript tag with our app config data
     * @return html
     */
    public static function config($include_msg = false)
    {
        $html = '<script>const config = {_token: "' . csrf_token() . '", url: "' . url('') . '", path: "' . \Request::path() . '", ajax_path: null};</script>';
        if ( $include_msg ) {
            $html .= self::msg();
        }
        return $html;
    }


    /**
     * return javascript info for stripe
     * @return html
     */
    public static function authorizeNetConfig()
    {
        return '<script>const authorizenet_config = {login_id: "' . env('AUTHORIZENET_MERCHANT_LOGIN_ID') . '", client_key: "' . env('AUTHORIZENET_MERCHANT_CLIENT_KEY') . '"};</script>';
    }


    /**
     * return javascript tag with our notification flash data
     * @return html
     */
    public static function msg()
    {
        if ( \Session::has('notification.status') && \Session::has('notification.message') ) {
            return '<script>const notification = {status: "' . \Session::get('notification.status') . '", message: "' . preg_replace('/\r|\n/', '', str_replace('"', "'", \Session::get('notification.message'))) . '"};</script>';
        } else {
            return '<script>const notification = null;</script>';
        }
    }




}