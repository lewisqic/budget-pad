<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Income extends BaseModel
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
        'company_id', 'category_id', 'amount', 'notes', 'date_at',
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'category_id' => 'required',
        'amount'      => 'required',
        'date_at'      => 'required',
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'category_id', 'amount', 'notes', 'date_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    // category
    public function category()
    {
        return $this->belongsTo('App\Models\Category');
    }

    // tags
    public function tags()
    {
        return $this->belongsToMany('App\Models\Tag');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * ATTRIBUTE ACCESSORS
     ******************************************************************/


    /******************************************************************
     * ATTRIBUTE MUTATORS
     ******************************************************************/

    /**
     * Set date format
     *
     * @param $value
     */
    public function setDateAtAttribute($value)
    {
        if ( !empty($value) ) {
            $this->attributes['date_at'] = date('Y-m-d', strtotime($value));
        }
    }


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


    /**
     * return all income for a specific company
     *
     * @param  int  $company_id
     * @param  bool $with_trashed
     *
     * @return collection
     */
    public static function getByCompany($company_id, $with_trashed = false)
    {
        $income = Income::where('company_id', $company_id)->when($with_trashed, function ($query) {
            return $query->withTrashed();
        })->get();
        return $income;
    }


}
