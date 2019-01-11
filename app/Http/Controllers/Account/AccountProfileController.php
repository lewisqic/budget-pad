<?php

namespace App\Http\Controllers\Account;

use App\Models\User;
use Facades\App\Services\UserService;
use App\Http\Controllers\Controller;

class AccountProfileController extends Controller {

    /**
     * show our edit administrator form page
     * @return view
     */
    public function index()
    {
        $data = [];
        return view('content.account.profile.index', $data);
    }

    /**
     * handle our profile update
     * @return redirect
     */
    public function update()
    {
        $user = UserService::load(\Auth::user()->id)->update(\Request::all());
        \Msg::success('Your profile has been <strong>updated</strong>');
        return redir('account/profile');
    }

}
