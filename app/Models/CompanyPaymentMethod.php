<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPaymentMethod extends BaseModel
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
        'company_id', 'payment_profile_id', 'cc_type', 'cc_last4', 'cc_expiration_month', 'cc_expiration_year', 'is_default'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'company_id'         => 'required',
        'payment_profile_id' => 'required'
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'is_default'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('\App\Models\Company');
    }

    // companies
    public function payments()
    {
        return $this->hasMany('\App\Models\CompanyPayment');
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


}
