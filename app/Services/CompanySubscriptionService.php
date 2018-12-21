<?php

namespace App\Services;

use App\Models\CompanySubscription;
use Facades\App\Services\CompanyPaymentService;

class CompanySubscriptionService extends BaseService
{


    /**
     * @var null
     */
    protected $subscription = null;


    /**
     * Load an existing subscription record
     *
     * @param  array  $id
     * @return object
     */
    public function load($id)
    {
        $this->subscription = CompanySubscription::findOrFail($id);
        return $this;
    }


    /**
     * create a new subscription record
     *
     * @param  array  $data
     * @return object
     */
    public function create($data)
    {
        // create subscription
        $subscription = CompanySubscription::create($data);
    }


    /**
     * update a subscription record
     *
     * @param  array  $data
     * @return object
     */
    public function update($data)
    {

        // update subscription
        $this->subscription->fill($data)->save();

        return $this->subscription;
    }


    /**
     * cancel all pending cancelation subscriptions
     */
    public function processPendingCancelations()
    {

        $subscriptions = CompanySubscription::whereNotNull('next_billing_at')
            ->where('next_billing_at', '<=', \Carbon::now()->format('Y-m-d'))
            ->where('status', 'Pending Cancelation')
            ->get();

        foreach ( $subscriptions as $subscription ) {

            // update subscription data
            $subscription->status = 'Canceled';
            $subscription->status_notes = 'Subscription was manually canceled.';
            $subscription->next_billing_at = null;
            $subscription->canceled_at = \Carbon::now();
            $subscription->save();

            // send cancelation confirmation email
            /*$data = [
                'cancelation_date' => \Carbon::now()->toFormattedDateString()
            ];
            \Mail::to($subscription->company->email)->send(new CancelationConfirmation($data));*/

        }

    }

    /**
     * charge a subscription plan
     */
    public function processSubscriptionPayments()
    {

        $subscriptions = CompanySubscription::with(['company.paymentMethods' => function ($query) {
                $query->where('is_default', true);
            }])->whereNotNull('next_billing_at')
            ->whereNotNull('term')
            ->where('next_billing_at', '<=', \Carbon::now()->format('Y-m-d'))
            ->where('status', 'Active')
            ->take(10)
            ->get();

        foreach ( $subscriptions as $subscription ) {
            try {

                // get default payment method
                $company = $subscription->company;
                if ( empty($company->customer_profile_id) ) {
                    continue;
                }
                $payment_method = $company->paymentMethods->first();
                if ( is_null($payment_method) ) {
                    fail('No default payment method found');
                }

                // charge credit card
                $result = CompanyPaymentService::chargeCustomer($company, $payment_method, $subscription->amount);

                // update subscription record
                $subscription->next_billing_at = $subscription->term == 'year' ? \Carbon::now()->addYear() : \Carbon::now()->addMonth();
                $subscription->save();

            } catch ( \Exception $e ) {

                // update subscription data
                $subscription->status = 'Canceled';
                $subscription->status_notes = 'Subscription canceled due to payment failure.';
                $subscription->next_billing_at = null;
                $subscription->canceled_at = \Carbon::now();
                $subscription->save();

                // send payment failed notification email
                /*$data = [
                    'amount' => \Format::currency($subscription->amount),
                    'date'   => \Carbon::now()->toFormattedDateString()
                ];
                \Mail::to($company->email)->send(new PaymentFailed($data));*/


            }
        }

    }


}