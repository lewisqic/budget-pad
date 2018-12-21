<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\LogsActivity;

class AdminSetting extends BaseModel
{
    use SoftDeletes, LogsActivity;


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'value'
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'value'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
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
     * return list of available setting tabs
     * @return array
     */
    public static function getTabs()
    {
        $settings = \DB::table('admin_settings')->select('tab', 'tab_order')->groupBy('tab', 'tab_order')->orderBy('tab_order')->get();
        $settings = $settings->pluck('tab')->toArray();
        return $settings;
    }

}
