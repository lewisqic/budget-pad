<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Facades\App\Services\ActivityLogService;
use Facades\App\Services\PermissionService;
use Illuminate\Http\Request;

class RoleService extends BaseService
{

    /**
     * @var null
     */
    protected $role = null;


    /**
     * Load an existing role record
     *
     * @param  array  $id
     * @return object
     */
    public function load($id)
    {
        $this->role = Role::findOrFail($id);
        return $this;
    }


    /**
     * create a new role
     *
     * @param  array  $data
     * @return object
     */
    public function create($data)
    {
        // determine default
        if ( $data['is_default'] ) {
            Role::where('guard_name', $data['guard_name'])->update(['is_default' => 0]);
        }
        // create role and permissions
        $role = Role::create($data);
        $role->syncPermissions(PermissionService::getPermissionsFromIds($data['permissions'] ?? []));
        return $role;
    }


    /**
     * update a role
     *
     * @param  array  $data
     * @return object
     */
    public function update($data)
    {

        // determine default
        if ( $data['is_default'] ) {
            Role::where('guard_name', $this->role->guard_name)->update(['is_default' => 0]);
        }

        // update role
        $this->role->fill($data)->save();

        // log any permission changes
        $permissions = $data['permissions'] ?? [];
        ActivityLogService::logRelationshipChange('permissions', $this->role,
            $this->role->permissions->implode('label', ', '),
            PermissionService::getPermissionsFromIds($permissions)->implode('label', ', ')
        );

        // sync permissions
        $this->role->syncPermissions(PermissionService::getPermissionsFromIds($permissions));

        return $this->role;
    }


    /**
     * Delete a role record
     * @param int $id
     *
     * @return object
     * @throws \AppExcp
     */
    public function delete($id)
    {
        $role = Role::with('users')->where('id', $id)->first();
        $count = Role::where('guard_name', $role->guard_name)->count();
        if ( $count == 1 ) {
            fail('You cannot delete the last role.');
        }
        if ( $role->users->count() > 0 ) {
            fail('Roles with attached users cannot be deleted.');
        }
        $role->delete();
        if ( $count == 2 ) {
            Role::where('guard_name', $role->guard_name)->update(['is_default' => 1]);
        }
        return $role;
    }


    /**
     * return array of user data for datatables
     * @return array
     */
    public function dataTables($data, $guard_name, $company_id = null)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $roles = Role::getByGuard($guard_name, $trashed);
        $roles_arr = [];
        $base = User::$types[\Auth::user()->type]['route'];
        foreach ( $roles as $role ) {
            $route = $base . ($base == 'admin' ? ($role->company_id ? '/member-roles/' : '/administrator-roles/') : '/roles/');
            $roles_arr[] = [
                'id' => $role->id,
                'class' => !is_null($role->deleted_at) ? 'text-danger' : null,
                'name' => $role->name,
                'is_default' => $role->is_default ? 'Yes' : 'No',
                'user_count' => $role->users->count(),
                'created_at' => [
                    'display' => $role->created_at->toFormattedDateString(),
                    'sort' => $role->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'click' => $route . $role->id,
                    'edit' => $route . $role->id . '/edit',
                    'delete' => $route . $role->id,
                    'restore' => !is_null($role->deleted_at) ? $route . $role->id : null,
                ])
            ];
        }
        return $roles_arr;
    }


    /**
     * Return collection of roles based on provided IDs
     * @param $roles_arr
     *
     * @return \Illuminate\Support\Collection
     */
    public function getRolesFromIds($roles_arr)
    {
        $roles = [];
        foreach ( $roles_arr as $role_id ) {
            $role = Role::find($role_id);
            if ( $role ) {
                $roles[] = $role;
            }
        }
        return collect($roles);
    }

}