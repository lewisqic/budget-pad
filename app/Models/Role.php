<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends \Spatie\Permission\Models\Role
{
    use SoftDeletes, LogsActivity, ValidatingTrait;


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'type', 'name', 'guard_name', 'is_default'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'name'       => 'required',
        'guard_name' => 'required',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at', 'updated_at', 'deleted_at'
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'name', 'is_default'
    ];

    /**
     * Setting to have model validation failure throw exception by default
     *
     * @var boolean
     */
    protected $throwValidationExceptions = true;

    /**
     * Set the default log name to be used
     *
     * @var string
     */
    protected static $logName = 'system';

    /**
     * Only log data that has been changed
     *
     * @var bool
     */
    protected static $logOnlyDirty = true;

    /**
     * Prevent certain attribute changes from triggering an activity log
     *
     * @var array
     */
    protected static $ignoreChangedAttributes = [
        'updated_at',
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
     * return all roles for a specific guard name
     *
     * @param  string $guard_name
     *
     * @return collection
     */
    public static function getByGuard($guard_name, $with_trashed = false)
    {
        $roles = Role::with('users')->when($with_trashed, function ($query) {
            return $query->withTrashed();
        })->where('guard_name', $guard_name)->orderBy('name', 'asc')->get();
        return $roles;
    }


    /**
     * Return global search results
     *
     * @param $keywords
     *
     * @return array
     */
    public function globalSearchResults($keywords)
    {
        $all_roles = Role::where('guard_name', User::$types[User::ADMINISTRATOR_ID]['route'])->where(function($query) use ($keywords) {
                foreach ( ['name'] as $column ) {
                    $query->orWhere(function($query) use ($keywords, $column) {
                        foreach ( $keywords as $keyword ) {
                            $query->where($column, 'LIKE', "%{$keyword}%");
                        }
                    });
                }
            })
            ->orderBy('name', 'ASC')
            ->limit(20)
            ->get();
        return [
            'order' => 6,
            'title' => 'Administrator Roles',
            'icon' => 'fa fa-key',
            'columns' => [
                'Name' => 'name', 'Default' => 'is_default', 'Date Added' => 'created_at'
            ],
            'link' => ['name' => url('admin/administrator-roles/{id}')],
            'results' => $all_roles,
        ];
    }


    /******************************************************************
     * EXTEND PARENT METHODS
     ******************************************************************/

    /**
     * @param \Spatie\Permission\Contracts\Permission|\Spatie\Permission\Contracts\Role $roleOrPermission
     */
    protected function ensureModelSharesGuard($roleOrPermission)
    {
        // we don't care if the guard names don't match
    }

}
