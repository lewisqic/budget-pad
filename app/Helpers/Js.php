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
    public static function stripeConfig()
    {

        return '<script>const stripe_config = {publishable_key: "' . (\Config::get('settings.stripe_publishable_key') ?: env('STRIPE_PUBLISHABLE_KEY')) . '"};</script>';
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