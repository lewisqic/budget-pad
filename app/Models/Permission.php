<?php

namespace App\Models;

class Permission extends \Spatie\Permission\Models\Permission
{


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'group', 'label', 'name', 'guard_name', 'order'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * ATTRIBUTE ACCESSORS
     ******************************************************************/


    /******************************************************************
     * ATTRIBUTE MUTATORS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM METHODS
     ******************************************************************/

    /**
     * return all permissions for a specific area
     *
     * @param  int  $type_id
     *
     * @return collection
     */
    public static function getByArea($area)
    {
        $permissions = Permission::where('guard_name', $area)->orderBy('order')->get();
        return $permissions;
    }

}
