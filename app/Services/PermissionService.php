<?php

namespace App\Services;

use App\Models\User;
use App\Models\Permission;
use Illuminate\Http\Request;

class PermissionService extends BaseService
{

    /**
     * Build permissions list based on the area value
     * @param $area
     *
     * @return array
     */
    public function buildPermissionsList($area)
    {
        $area_permissions = Permission::getByArea($area);
        $permissions = [];
        foreach ( $area_permissions as $perm ) {
            $id = isset($permissions[$perm->group][$perm->label]) ? $permissions[$perm->group][$perm->label] . '|' . $perm->id : $perm->id;
            $permissions[$perm->group][$perm->label] = $id;
        }
        return $permissions;
    }

    /**
     * Format existing permissions into readable array list
     * @param $permissions
     */
    public function formatExistingPermissions($existing_permissions)
    {
        $permissions = [];
        foreach ( $existing_permissions as $perm ) {
            $permissions[$perm->group][$perm->label] = $perm->label;
        }
        return $permissions;
    }

    /**
     * Return collection of permissions based on provided IDs
     * @param $permissions_arr
     *
     * @return \Illuminate\Support\Collection
     */
    public function getPermissionsFromIds($permissions_arr)
    {
        $permissions = [];
        foreach ( $permissions_arr as $perm_id ) {
            foreach ( explode('|', $perm_id) as $id ) {
                $permission = Permission::find($id);
                if ( $permission ) {
                    $permissions[] = $permission;
                }
            }
        }
        return collect($permissions);
    }

}