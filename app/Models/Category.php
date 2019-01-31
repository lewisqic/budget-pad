<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends BaseModel
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
        'company_id', 'category_type', 'amount_type', 'name', 'budget'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'category_type' => 'required',
        'amount_type'   => 'required',
        'name'          => 'required',
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'category_type', 'amount_type', 'name', 'budget'
    ];

    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }

    // incomes
    public function incomes()
    {
        return $this->hasMany('App\Models\Income');
    }

    // expenses
    public function expenses()
    {
        return $this->hasMany('App\Models\Expense');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


    /**
     * return all categories for a specific company
     *
     * @param  int  $company_id
     * @param  bool $with_trashed
     *
     * @return collection
     */
    public static function getByCompany($company_id, $with_trashed = false)
    {
        $categories = Category::where('company_id', $company_id)->when($with_trashed, function ($query) {
            return $query->withTrashed();
        })->orderBy('name', 'ASC')->get();
        return $categories;
    }


}
