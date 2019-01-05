<?php

namespace App\Services;

use App\Models\CompanyPayment;

class CompanyPaymentService extends BaseService
{


    /**
     * @var null
     */
    protected $payment = null;


    /**
     * Load an existing payment record
     *
     * @param  array $id
     *
     * @return object
     */
    public function load($id)
    {
        $this->payment = CompanyPayment::findOrFail($id);
        return $this;
    }


    /**
     * create a new payment record
     *
     * @param  array $data
     *
     * @return object
     */
    public function create($data)
    {
        // create payment
        $payment = CompanyPayment::create($data);
    }


    /**
     * update a payment record
     *
     * @param  array $data
     *
     * @return object
     */
    public function update($data)
    {

        // update payment
        $this->payment->fill($data)->save();

        return $this->payment;
    }


    /**
     * charge a customers source
     *
     * @param  array $data
     *
     * @throws \AppExcp
     * @return array
     */
    public function chargeCustomer($company, $payment_method, $amount)
    {

        // Charge the Customer instead of the card:
        $charge = \Stripe\Charge::create([
            'amount'   => $amount * 100,
            'currency' => "USD",
            'customer' => $company->stripe_customer_id,
            'source'   => $payment_method->stripe_source_id ?: null
        ]);

        // create payment record
        $this->create([
            'company_id'                => $company->id,
            'company_subscription_id'   => $company->subscription->id,
            'company_payment_method_id' => $payment_method->id,
            'stripe_charge_id'          => $charge->id,
            'amount'                    => $amount,
            'notes'                     => ucwords($company->subscription->term . 'ly subscription fee'),
            'status'                    => 'Complete'
        ]);

        // return charge id
        return $charge->id;

    }

    /**
     * Refund a stripe charge
     *
     * @param $id
     *
     * @return \Stripe\ApiResource
     */
    public function refundCharge($id)
    {
        $payment = CompanyPayment::findOrFail($id);
        $refund = \Stripe\Refund::create([
            'charge' => $payment->stripe_charge_id
        ]);
        // update payment record
        $this->load($id)->update([
            'stripe_refund_id' => $refund->id,
            'status'           => 'Refunded',
            'refunded_at'      => \Carbon::now(),
        ]);
        return $refund;
    }


}