<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use Facades\App\Services\UserService;
use Facades\App\Services\PermissionService;
use App\Http\Controllers\Controller;


class AdminAdministratorController extends Controller
{

    /**
     * Show the administrators list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.admin.administrators.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = UserService::dataTables(\Request::all(), User::ADMINISTRATOR_ID, null);
        return response()->json($data);
    }

    /**
     * Show the administrators create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Add',
            'all_permissions' => PermissionService::buildPermissionsList(User::$types[User::ADMINISTRATOR_ID]['route']),
            'roles' => Role::getByGuard(User::$types[User::ADMINISTRATOR_ID]['route'])
        ];
        return view('content.admin.administrators.create-edit', $data);
    }

    /**
     * Show the administrators create page
     *
     * @return view
     */
    public function edit($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'all_permissions' => PermissionService::buildPermissionsList(User::$types[User::ADMINISTRATOR_ID]['route']),
            'roles' => Role::getByGuard(User::$types[User::ADMINISTRATOR_ID]['route']),
            'user' => $user,
            'user_permissions' => $user->getDirectPermissions()->pluck('id')->toArray(),
        ];
        return view('content.admin.administrators.create-edit', $data);
    }

    /**
     * Show an administrator
     *
     * @return view
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $data = [
            'user' => $user,
            'user_permissions' => PermissionService::formatExistingPermissions($user->permissions)
        ];
        return view('content.admin.administrators.show', $data);
    }

    /**
     * Create new administrator record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $data['type'] = User::ADMINISTRATOR_ID;
        $user = UserService::create($data);
        \Msg::success($user->name . ' has been <strong>added</strong>');
        return redir('admin/administrators');
    }

    /**
     * Create new administrator record
     *
     * @return redirect
     */
    public function update()
    {
        $user = UserService::load(\Request::input('id'))->update(\Request::all());
        \Msg::success($user->name . ' has been <strong>updated</strong>');
        return redir('admin/administrators');
    }

    /**
     * Delete an administrator record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        if ( $id == \Auth::user()->id ) {
            fail('Currently logged in user cannot be deleted');
        } else {
            $user = UserService::delete($id);
            \Msg::success($user->name . ' has been <strong>deleted</strong> ' . \Html::undoLink('admin/administrators/' . $user->id));
        }
        return redir('admin/administrators');
    }

    /**
     * Restore an administrator record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $user = UserService::restore($id);
        \Msg::success($user->name . ' has been <strong>restored</strong>');
        return redir('admin/administrators');
    }


}
