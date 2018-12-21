<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanySubscription extends BaseModel
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
        'company_id', 'amount', 'term', 'status', 'status_notes', 'next_billing_at', 'expires_at', 'canceled_at'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'company_id'      => 'required'
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'amount', 'term', 'status', 'status_notes', 'next_billing_at', 'expires_at', 'canceled_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('App\Models\Company');
    }
    // company_payments
    public function payments()
    {
        return $this->hasMany('App\Models\CompanyPayment');
    }
    // company_payment_methods
    public function paymentMethods()
    {
        return $this->hasMany('App\Models\CompanyPaymentMethod');
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
    public function setExpiresAtAttribute($value)
    {
        if ( !empty($value) ) {
            $this->attributes['expires_at'] = date('Y-m-d', strtotime($value));
        }
    }

    /**
     * Set date format
     *
     * @param $value
     */
    public function setNextBillingAtAttribute($value)
    {
        if ( !empty($value) ) {
            $this->attributes['next_billing_at'] = date('Y-m-d', strtotime($value));
        }
    }


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/



}
