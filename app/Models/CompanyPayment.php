<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class CompanyPayment extends BaseModel
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
        'company_id', 'company_subscription_id', 'company_payment_method_id', 'transaction_id', 'refund_id', 'amount', 'currency', 'notes', 'status', 'refunded_at'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'company_id'                => 'required',
        'company_subscription_id'   => 'required',
        'company_payment_method_id' => 'required',
        'amount'                    => 'required',
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'notes', 'status', 'refunded_at'
    ];


    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // companies
    public function company()
    {
        return $this->belongsTo('\App\Models\Company');
    }

    // subscriptions
    public function subscription()
    {
        return $this->belongsTo('\App\Models\CompanySubscription', 'company_subscription_id');
    }

    // company_payment_methods
    public function paymentMethod()
    {
        return $this->belongsTo('\App\Models\CompanyPaymentMethod', 'company_payment_method_id')->withTrashed();
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
    public function setRefundedAtAttribute($value)
    {
        if ( !empty($value) ) {
            $this->attributes['refunded_at'] = date('Y-m-d H:i:s', is_int($value) ? $value : strtotime($value));
        }
    }

    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/


    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/


}
