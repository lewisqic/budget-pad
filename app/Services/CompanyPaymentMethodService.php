<?php

namespace App\Services;

use App\Models\CompanyPaymentMethod;

class CompanyPaymentMethodService extends BaseService
{


    /**
     * @var null
     */
    protected $paymentMethod = null;


    /**
     * Load an existing payment method record
     *
     * @param  array  $id
     * @return object
     */
    public function load($id)
    {
        $this->paymentMethod = CompanyPaymentMethod::findOrFail($id);
        return $this;
    }


    /**
     * create a new payment method record
     *
     * @param  array  $data
     * @return array
     */
    public function create($data)
    {
        // create payment method
        $payment_method = CompanyPaymentMethod::create($data);
        return $payment_method;
    }


    /**
     * update a payment method record
     *
     * @param  array  $data
     * @return object
     */
    public function update($data)
    {
        // update company
        $this->paymentMethod->fill($data)->save();
        return $this->paymentMethod;
    }


    public function updateAll($company, $data)
    {

        // delete any payments methods
        if ( !empty($data['delete']) ) {
            foreach ( $data['delete'] as $id ) {
                CompanyPaymentMethod::destroy($id);
            }
        }

        // set new default method if needed
        if ( !empty($data['default']) ) {
            foreach ( CompanyPaymentMethod::where('company_id', $company->id)->get() as $method ) {
                $new_value = $method->id == $data['default'] ? true : false;
                if ( $new_value != $method->is_default ) {
                    $method->is_default = $new_value;
                    $method->save();
                }
            }
        }

        // create new stripe payment source if necessary
        if ( !empty($data['token']) ) {

            $stripe_customer = \Stripe\Customer::retrieve($company->stripe_customer_id);
            $source = $stripe_customer->sources->create([
                'source' => $data['token']
            ]);

            // create company payment method
            $this->create([
                'company_id'          => $company->id,
                'stripe_source_id'    => $source->id,
                'cc_type'             => $source->brand,
                'cc_last4'            => $source->last4,
                'cc_expiration_month' => $source->exp_month,
                'cc_expiration_year'  => $source->exp_year
            ]);

        }
        
    }



}