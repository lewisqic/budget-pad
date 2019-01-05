<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use App\Models\Permission;
use Facades\App\Services\RoleService;
use Facades\App\Services\CompanyPaymentMethodService;

class CompanyService extends BaseService
{


    /**
     * @var null
     */
    protected $company = null;


    /**
     * Load an existing company record
     *
     * @param  array $id
     *
     * @return object
     */
    public function load($id)
    {
        $this->company = Company::findOrFail($id);
        return $this;
    }


    /**
     * create a new company record
     *
     * @param  array $data
     *
     * @return array
     */
    public function create($data, $payment_data = [])
    {
        $result = \DB::transaction(function () use ($data, $payment_data) {

            // create company
            $company = Company::create($data);

            // create stripe profile and payment method if needed
            if ( !empty($payment_data['token']) ) {

                // create a new stripe customer
                $stripe_customer = $this->createStripeCustomer([
                    'name'  => $data['name'],
                    'email' => $data['email'],
                    'token' => $payment_data['token']
                ]);

                // update the company record
                $company->stripe_customer_id = $stripe_customer->id;
                $company->save();

                // create company payment method
                CompanyPaymentMethodService::create([
                    'company_id'          => $company->id,
                    'stripe_source_id'    => $stripe_customer->sources->data[0]->id,
                    'cc_type'             => $stripe_customer->sources->data[0]->brand,
                    'cc_last4'            => $stripe_customer->sources->data[0]->last4,
                    'cc_expiration_month' => $stripe_customer->sources->data[0]->exp_month,
                    'cc_expiration_year'  => $stripe_customer->sources->data[0]->exp_year,
                    'is_default'          => true
                ]);
            }

            // create default role
            $role = RoleService::create([
                'company_id' => $company->id,
                'name'       => 'Default',
                'guard_name' => User::$types[User::MEMBER_ID]['route'] . '-' . $company->id,
                'is_default' => true,
            ]);

            // assign all permissions to role
            $role->givePermissionTo(Permission::where('guard_name', 'account')->get());

            return [
                'company' => $company,
            ];
        });
        return $result['company'];
    }


    /**
     * update a company record
     *
     * @param  array $data
     *
     * @return object
     */
    public function update($data, $payment_data = [])
    {

        // create stripe customer profile if needed
        if ( empty($this->company->stripe_customer_id) && !empty($payment_data['token']) ) {

            // create a new stripe customer
            $stripe_customer = $this->createStripeCustomer([
                'name'  => $data['name'],
                'email' => $data['email'],
                'token' => $payment_data['token']
            ]);

            // update the company record
            $data['stripe_customer_id'] = $stripe_customer->id;

            // create company payment method
            CompanyPaymentMethodService::create([
                'company_id'          => $this->company->id,
                'stripe_source_id'    => $stripe_customer->sources->data[0]->id,
                'cc_type'             => $stripe_customer->sources->data[0]->brand,
                'cc_last4'            => $stripe_customer->sources->data[0]->last4,
                'cc_expiration_month' => $stripe_customer->sources->data[0]->exp_month,
                'cc_expiration_year'  => $stripe_customer->sources->data[0]->exp_year,
                'is_default'          => true
            ]);

        }

        // update company
        $this->company->fill($data)->save();
        return $this->company;
    }

    /**
     * create a stripe customer record
     *
     * @param  array $data [description]
     *
     * @return array
     */
    public function createStripeCustomer($data)
    {

        $customer = \Stripe\Customer::create([
            'description' => $data['name'],
            'email'       => $data['email'],
            'source'      => $data['token']
        ]);

        return $customer;

    }


}