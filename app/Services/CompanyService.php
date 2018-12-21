<?php

namespace App\Services;

use App\Models\User;
use App\Models\Company;
use App\Models\Permission;
use Facades\App\Services\RoleService;
use Facades\App\Services\CompanyPaymentMethodService;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

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

            // create authorize.net customer profile if needed
            if ( !empty($payment_data['dataValue']) ) {
                // create our customer profile
                $payment_data['company'] = $company;
                $profile = $this->createAuthorizenetProfile($payment_data);
                // update the company record
                $company->customer_profile_id = $profile['customer_profile_id'];
                $company->save();
                // get details on the payment profile
                $payment_profile = CompanyPaymentMethodService::getAuthorizenetPaymentProfile($profile['customer_profile_id'], $profile['payment_profile_id']);
                // create company payment method
                CompanyPaymentMethodService::create([
                    'company_id'          => $company->id,
                    'payment_profile_id'  => $profile['payment_profile_id'],
                    'cc_type'             => $payment_profile['cc_type'],
                    'cc_last4'            => $payment_profile['cc_last4'],
                    'cc_expiration_month' => $payment_data['cc_expiration_month'],
                    'cc_expiration_year'  => $payment_data['cc_expiration_year'],
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

        // create authorize.net customer profile if needed
        if ( empty($this->company->customer_profile_id) && !empty($payment_data['dataValue']) ) {
            // create our customer profile
            $payment_data['company'] = $this->company;
            $profile = $this->createAuthorizenetProfile($payment_data);
            // update the company record
            $data['customer_profile_id'] = $profile['customer_profile_id'];
            // get details on the payment profile
            $payment_profile = CompanyPaymentMethodService::getAuthorizenetPaymentProfile($profile['customer_profile_id'], $profile['payment_profile_id']);
            // create company payment method
            CompanyPaymentMethodService::create([
                'company_id'          => $this->company->id,
                'payment_profile_id'  => $profile['payment_profile_id'],
                'cc_type'             => $payment_profile['cc_type'],
                'cc_last4'            => $payment_profile['cc_last4'],
                'cc_expiration_month' => $payment_data['cc_expiration_month'],
                'cc_expiration_year'  => $payment_data['cc_expiration_year'],
                'is_default'          => true
            ]);
        }

        // update company
        $this->company->fill($data)->save();
        return $this->company;
    }

    /**
     * Create an Authorize.Net customer profile
     *
     * @param $data
     *
     * @throws \AppExcp
     *
     * @return array
     */
    public function createAuthorizenetProfile($data)
    {
        if ( empty($data['dataValue']) ) {
            return ['customer_profile_id' => null, 'payment_profile_id' => null];
        }

        // Create a merchantAuthenticationType object with authentication details
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_MERCHANT_TRANSACTION_KEY'));

        // create opaque payment data
        $op = new AnetAPI\OpaqueDataType();
        $op->setDataDescriptor($data['dataDescriptor']);
        $op->setDataValue($data['dataValue']);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($op);

        // Create a new Customer Payment Profile object
        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setPayment($paymentOne);
        $paymentprofile->setDefaultPaymentProfile(true);
        $paymentprofiles[] = $paymentprofile;

        // Create a new CustomerProfileType and add the payment profile object
        $customerprofile = new AnetAPI\CustomerProfileType();
        $customerprofile->setMerchantCustomerId($data['company']->id);
        $customerprofile->setEmail($data['company']->email);
        $customerprofile->setPaymentProfiles($paymentprofiles);

        // Assemble the complete transaction request
        $request = new AnetAPI\CreateCustomerProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId('ref' . time());
        $request->setProfile($customerprofile);

        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerProfileController($request);
        $response = $controller->executeWithApiResponse(env('APP_ENV') == 'production' ? \net\authorize\api\constants\ANetEnvironment::PRODUCTION : \net\authorize\api\constants\ANetEnvironment::SANDBOX);

        // handle our response
        if ( $response != null && $response->getMessages()->getResultCode() == "Ok" ) {
            $paymentProfiles = $response->getCustomerPaymentProfileIdList();
            return [
                'customer_profile_id' => $response->getCustomerProfileId(),
                'payment_profile_id'  => $paymentProfiles[0]
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            fail($errorMessages[0]->getText());
        }


    }


}