<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Facades\App\Services\UserService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class AuthIndexController extends Controller
{


    /**
     * Show the login page
     *
     * @return view
     */
    public function showLogin()
    {
        return view('content.auth.index.login');
    }


    /**
     * show the forgot password page
     * @return view
     */
    public function showForgot()
    {
        return view('content.auth.index.forgot');
    }


    /**
     * show the reset password page
     * @param string $token
     * @return view
     */
    public function showReset($token)
    {
        $resets = \DB::table('password_resets')->orderBy('created_at', 'desc')->get();
        $email = '';
        foreach ( $resets as $reset ) {
            if ( \Hash::check($token, $reset->token) ) {
                $email = $reset->email;
                break;
            }
        }
        if ( empty($email) ) {
            fail('Invalid password reset link.');
        }
        $data = [
            'email' => $email,
            'token' => $token
        ];
        return view('content.auth.index.reset', $data);
    }


    /**
     * Handle our login request
     *
     * @return json
     */
    public function handleLogin(Request $request)
    {
        $response = UserService::login($request);
        return response()->json(['success' => true, 'route' => $response['route']]);
    }


    /**
     * handle our password reminder request
     * @return redirect
     */
    public function handleForgot(Request $request)
    {
        $response = UserService::sendResetLinkEmail($request);
        \Msg::success($response['message']);
        return response()->json(['success' => true, 'route' => $response['route']]);
    }


    /**
     * handle our reset password request
     * @return redirect
     */
    public function handleReset(Request $request)
    {
        $response = UserService::reset($request);
        \Msg::success($response['message']);
        return response()->json(['success' => true, 'route' => $response['route']]);
    }


    /**
     * handle our logout request
     * @return redirect
     */
    public function handleLogout(Request $request)
    {
        $response = UserService::logout($request);
        \Msg::success($response['message']);
        return redir($response['route']);
    }


}
