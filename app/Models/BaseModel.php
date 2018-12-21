<?php

namespace App\Models;

use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    use ValidatingTrait;


    /******************************************************************
     * MODEL PROPERTIES
     ******************************************************************/

    /**
     * Validation rules array
     *
     * @var array
     */
    protected $rules = [];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'refunded_at',
        'expires_at',
        'next_billing_at',
        'canceled_at',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'array',
        'adminly_settings' => 'array'
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
        'adminly_settings',
        'updated_at',
    ];


    /******************************************************************
     * CUSTOM ATTRIBUTE ACCESSORS
     ******************************************************************/


    /**
     * Get some data from our json data column
     *
     * @param string $key
     * @return mixed
     */
    public function getData($key = null)
    {
        if ( is_null($key) ) {
            return $this->data;
        }
        $data = $this->data;
        $flattened = array_dot($data);
        $key_parts = explode('.', trim($key));
        $dot_data = $data;
        $has_dot_data = false;
        foreach ( $key_parts as $key_part ) {
            if ( array_key_exists($key_part, $dot_data) ) {
                $has_dot_data = true;
                $dot_data = $dot_data[$key_part];
            }
        }
        if ( array_key_exists($key, $data) ) {
            return $data[$key];
        } elseif ( array_key_exists($key, $flattened) ) {
            return $flattened[$key];
        } elseif ( $has_dot_data ) {
            return $dot_data;
        } else {
            return null;
        }
    }


    /******************************************************************
     * ATTRIBUTE MUTATORS
     ******************************************************************/


    /**
     * Set data on our json data column
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function setData($key, $value)
    {
        $data = $this->data;
        array_set($data, $key, $value);
        $this->attributes['data'] = json_encode($data);
    }


    /******************************************************************
         * CUSTOM SCOPE METHODS
     ******************************************************************/


    public function scopeWhereContains($query, $path, $value) {
        $path_arr = explode('->', $path);
        $column = $path_arr[0];
        unset($path_arr[0]);
        $sub_path = count($path_arr) > 0 ? '$.' . implode('.', $path_arr) : '';
        $sql = "JSON_CONTAINS({$column}, '{$value}'" . (!empty($sub_path) ? ", '{$sub_path}')" : ")");
        return $query->whereRaw($sql);
    }


}
