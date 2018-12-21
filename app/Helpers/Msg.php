<?php

namespace App\Helpers;

class Msg {

    /**
     * Flash our notification data to the session
     * @param string $status
     * @param string $message
     */
    public static function flash($status, $message)
    {
        \Session::flash('notification.status', $status);
        \Session::flash('notification.message', $message);
    }

    /*
     * Show all messages
     */
    public static function show()
    {
        if ( \Session::has('notification.status') && \Session::has('notification.message') ) {
            $icon = '';
            switch ( \Session::get('notification.status') ) {
                case 'success':
                    $icon = 'check-circle';
                    break;
                case 'info':
                    $icon = 'info-circle';
                    break;
                case 'warning':
                    $icon = 'exclamation-circle';
                    break;
                case 'danger':
                    $icon = 'exclamation-triangle';
                    break;
            }
            return '<div class="alert alert-alt alert-' . \Session::get('notification.status') .' alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        ' . (!empty($icon) ? '<i class="fa fa-' . $icon . '"></i>' : '') . '
                        ' . \Session::get('notification.message') . '
                    </div>';
        }
        return '';
    }

    /**
     * Success message
     * @param string $message
     */
    public static function success($message)
    {
        \Msg::flash('success', $message);
    }

    /**
     * info message
     * @param string $message
     */
    public static function info($message)
    {
        \Msg::flash('info', $message);
    }

    /**
     * warning message
     * @param string $message
     */
    public static function warning($message)
    {
        \Msg::flash('warning', $message);
    }

    /**
     * danger message
     * @param string $message
     */
    public static function danger($message)
    {
        \Msg::flash('danger', $message);
    }

}