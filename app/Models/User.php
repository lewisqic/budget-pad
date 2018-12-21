<?php

namespace App\Models;

use Spatie\Permission\Traits\HasRoles;
use Spatie\Activitylog\Traits\HasActivity;
use Yadahan\AuthenticationLog\AuthenticationLogable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;

class User extends BaseModel implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use SoftDeletes, Notifiable, Authenticatable, Authorizable, CanResetPassword, HasActivity, AuthenticationLogable, HasRoles;


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id', 'type', 'first_name', 'last_name', 'email', 'password', 'avatar', 'superuser', 'custom_permissions', 'company_owner', 'adminly_settings', 'data'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'first_name' => 'required|between:2,80',
        'last_name'  => 'required|between:2,80',
        'email'      => 'required|between:5,64|email|unique:users,email',
        'password'   => 'required|min:6'
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'first_name', 'last_name', 'email', 'password', 'avatar', 'superuser', 'custom_permissions', 'company_owner'
    ];

    /**
     * The guard name that our user will use for roles/permissions
     *
     * @var string
     */
    protected static $guard_name = null;


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/

    public function company()
    {
        return $this->belongsTo('\App\Models\Company');
    }


    /******************************************************************
     * MODEL EVENTS VIA BOOT
     ******************************************************************/

    public static function boot()
    {
        parent::boot();
        self::saved(function($model) {
            // update our guard name
            if ( $model->type == self::ADMINISTRATOR_ID ) {
                self::$guard_name = self::$types[self::ADMINISTRATOR_ID]['route'];
            } elseif ( $model->type == self::MEMBER_ID ) {
                self::$guard_name = self::$types[self::MEMBER_ID]['route'] . '-' . $model->company_id;
            }
        });
    }


    /******************************************************************
     * ATTRIBUTE ACCESSORS
     ******************************************************************/

    /**
     * Get the user's full name.
     *
     * @return string
     */
    public function getNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }


    /******************************************************************
     * ATTRIBUTE MUTATORS
     ******************************************************************/

    /**
     * Hash password if needed
     *
     * @param $value
     */
    public function setPasswordAttribute($value)
    {
        // skip values that already appear to be hashed
        if ( strlen($value) == 60 && preg_match('/^\$2y\$/', $value) ) {
            return;
        }
        if ( is_null($value) ) {
            $this->attributes['password'] = $this->attributes['password'];
        } else {
            $this->attributes['password'] = \Hash::make($value);
        }
    }


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/

    /**
     * Declare the ID values of our different user types
     */
    const ADMINISTRATOR_ID = 1;
    const MEMBER_ID = 2;

    /**
     * Declare our user types and their default route and database table
     *
     * @var array
     */
    public static $types = [
        self::ADMINISTRATOR_ID => [
            'route' => 'admin'
        ],
        self::MEMBER_ID        => [
            'route' => 'account'
        ],
    ];

    /**
     * Define all the color options available in adminly theme
     *
     * @var array
     */
    public static $adminlyColors = ['black', 'blue', 'brown', 'cyan', 'green', 'grey', 'lavender', 'maroon', 'navy', 'orange', 'pink', 'pumpkin', 'purple', 'red', 'teal', 'yellow'];

    /**
     * Define all the style options available in adminly theme
     *
     * @var array
     */
    public static $adminlyStyles = ['primary', 'secondary', 'success', 'info', 'warning', 'danger'];

    /**
     * Define our default user adminly settings
     *
     * @var array
     */
    public static $adminlyDefaults = [
        'layout' => ['header_style' => 'normal', 'submenu_style' => 'bar'],
        'colors' => ['primary' => 'blue', 'secondary' => 'grey', 'success' => 'green', 'info' => 'purple', 'warning' => 'orange', 'danger' => 'red'],
        'favorites' => []
    ];

    /******************************************************************
     * CUSTOM ORM METHODS
     ******************************************************************/

    /**
     * return all users for a specific user type
     *
     * @param  int  $type
     * @param  bool $with_trashed
     * @param  int  $company_id
     *
     * @return collection
     */
    public static function getByType($type, $with_trashed = false, $company_id = null)
    {
        $users = User::where('type', $type)->when($with_trashed, function ($query) {
                return $query->withTrashed();
            })->when($company_id !== null, function ($query) use ($company_id) {
                $query->where('company_id', $company_id);
            })->get();
        return $users;
    }

    /**
     * Find a user based on email
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function findByEmail($email)
    {
        $user = User::where('email', $email)->first();
        return $user;
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
        $all_users = User::where(function($query) use ($keywords) {
                foreach ( ['first_name', 'last_name', 'email'] as $column ) {
                    $query->orWhere(function($query) use ($keywords, $column) {
                        foreach ( $keywords as $keyword ) {
                            $query->where($column, 'LIKE', "%{$keyword}%");
                        }
                    });
                }
            })
            ->orderBy('first_name', 'ASC')
            ->limit(20)
            ->get();

        $users = [self::ADMINISTRATOR_ID => [], self::MEMBER_ID => []];
        foreach ( $all_users as $user ) {
            $users[$user->type][] = $user;
        }
        return [
            [
                'order' => 1,
                'title' => 'Members',
                'icon' => 'fa fa-users',
                'columns' => [
                    'Name' => 'name', 'Email' => 'email', 'Date Added' => 'created_at'
                ],
                'link' => ['name' => url('admin/members/{id}')],
                'results' => collect($users[self::MEMBER_ID]),
            ],
            [
                'order' => 5,
                'title' => 'Administrators',
                'icon' => 'fa fa-user-tie',
                'columns' => [
                    'Name' => 'name', 'Email' => 'email', 'Date Added' => 'created_at'
                ],
                'link' => ['name' => url('admin/administrators/{id}')],
                'results' => collect($users[self::ADMINISTRATOR_ID]),
            ]
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
