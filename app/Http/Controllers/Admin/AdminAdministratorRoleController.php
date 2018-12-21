<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use App\Models\Permission;
use Facades\App\Services\RoleService;
use Facades\App\Services\PermissionService;
use App\Http\Controllers\Controller;


class AdminAdministratorRoleController extends Controller
{

    /**
     * Show the roles list page
     *
     * @return view
     */
    public function index()
    {
        
        return view('content.admin.administrator-roles.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = RoleService::dataTables(\Request::all(), User::$types[User::ADMINISTRATOR_ID]['route']);
        return response()->json($data);
    }

    /**
     * Show the roles create page
     *
     * @return view
     */
    public function create()
    {
        $data = [
            'title' => 'Add',
            'all_permissions' => PermissionService::buildPermissionsList(User::$types[User::ADMINISTRATOR_ID]['route']),
        ];
        return view('content.admin.administrator-roles.create-edit', $data);
    }

    /**
     * Show the roles create page
     *
     * @return view
     */
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $data = [
            'title' => 'Edit',
            'role' => $role,
            'all_permissions' => PermissionService::buildPermissionsList(User::$types[User::ADMINISTRATOR_ID]['route']),
            'role_permissions' => $role->permissions->pluck('id')->toArray()

        ];
        return view('content.admin.administrator-roles.create-edit', $data);
    }

    /**
     * Show an role
     *
     * @return view
     */
    public function show($id)
    {
        $role = Role::findOrFail($id);
        $data = [
            'role' => $role,
            'role_permissions' => PermissionService::formatExistingPermissions($role->permissions)
        ];
        return view('content.admin.administrator-roles.show', $data);
    }

    /**
     * Create new role record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $data['guard_name'] = User::$types[User::ADMINISTRATOR_ID]['route'];
        $role = RoleService::create($data);
        \Msg::success($role->name . ' role has been <strong>added</strong>.');
        return redir('admin/administrator-roles');
    }

    /**
     * Create new role record
     *
     * @return redirect
     */
    public function update()
    {
        $role = RoleService::load(\Request::input('id'))->update(\Request::all());
        \Msg::success($role->name . ' role has been <strong>updated</strong>.');
        return redir('admin/administrator-roles');
    }

    /**
     * Delete an role record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $role = RoleService::delete($id);
        \Msg::success($role->name . ' role has been <strong>deleted</strong> ' . \Html::undoLink('admin/administrator-roles/' . $role->id));
        return redir('admin/administrator-roles');
    }

    /**
     * Restore an role record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $role = RoleService::restore($id);
        \Msg::success($role->name . ' role has been <strong>restored</strong>');
        return redir('admin/administrator-roles');
    }


}