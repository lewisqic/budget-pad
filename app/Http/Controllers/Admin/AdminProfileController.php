<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Facades\App\Services\UserService;
use App\Http\Controllers\Controller;

class AdminProfileController extends Controller {

    /**
     * show our edit administrator form page
     * @return view
     */
    public function index()
    {
        // TODO: implement profile image feature
        $data = [];
        return view('content.admin.profile.index', $data);
    }

    /**
     * handle our administration creation request
     * @return redirect
     */
    public function update()
    {
        $user = UserService::load(\Auth::user()->id)->update(\Request::all());
        \Msg::success('Your profile has been <strong>updated</strong>');
        return redir('admin/profile');
    }

}
