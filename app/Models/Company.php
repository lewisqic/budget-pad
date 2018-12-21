<?php

namespace App\Models;

use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends BaseModel
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
        'customer_profile_id', 'name', 'email', 'phone', 'address', 'website', 'logo_image', 'currency', 'language', 'mail', 'payment'
    ];

    /**
     * Declare rules for validation
     *
     * @var array
     */
    protected $rules = [
        'email'      => 'required|email'
    ];

    /**
     * Set the model attributes that should be logged for each activity log
     *
     * @var array
     */
    protected static $logAttributes = [
        'name', 'email', 'phone', 'address', 'website', 'logo_image', 'currency', 'language', 'mail', 'payment'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'mail' => 'array',
        'payment' => 'array',
    ];

    /******************************************************************
     * MODEL RELATIONSHIPS
     ******************************************************************/


    // users
    public function users()
    {
        return $this->hasMany('App\Models\User');
    }
    // company_subscriptions
    public function subscription()
    {
        return $this->hasOne('App\Models\CompanySubscription');
    }
    // company_payment_methods
    public function paymentMethods()
    {
        return $this->hasMany('App\Models\CompanyPaymentMethod')->orderBy('is_default', 'desc');
    }
    // company_payments
    public function payments()
    {
        return $this->hasMany('App\Models\CompanyPayment');
    }


    /******************************************************************
     * MODEL HOOKS
     ******************************************************************/


    /******************************************************************
     * CUSTOM  PROPERTIES
     ******************************************************************/

    const DEFAULT_CURRENCY = 'USD';
    const DEFAULT_LANGUAGE = 'English';

    /******************************************************************
     * CUSTOM ORM ACTIONS
     ******************************************************************/



}
