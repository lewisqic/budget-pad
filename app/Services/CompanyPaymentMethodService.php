<?php

namespace App\Services;

use App\Models\CompanyPaymentMethod;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

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

        // create new authorize.net payment profile if necessary
        if ( !empty($data['dataValue']) ) {
            // create payment profile
            $payment_profile_id = $this->createAuthorizenetPaymentProfile($company->customer_profile_id, $data);
            // get details on the payment profile
            $payment_profile = $this->getAuthorizenetPaymentProfile($company->customer_profile_id, $payment_profile_id);
            // create company payment method
            CompanyPaymentMethodService::create([
                'company_id'          => $company->id,
                'payment_profile_id'  => $payment_profile_id,
                'cc_type'             => $payment_profile['cc_type'],
                'cc_last4'            => $payment_profile['cc_last4'],
                'cc_expiration_month' => $data['cc_expiration_month'],
                'cc_expiration_year'  => $data['cc_expiration_year'],
            ]);
        }
        
    }


    /**
     * Get the details on an authorize.net payment profile
     *
     * @param $customer_profile_id
     * @param $payment_profile_id
     *
     * @return array
     * @throws \AppExcp
     */
    public function getAuthorizenetPaymentProfile($customer_profile_id, $payment_profile_id)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_MERCHANT_TRANSACTION_KEY'));
        $request = new AnetAPI\GetCustomerPaymentProfileRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId('ref' . time());
        $request->setCustomerProfileId($customer_profile_id);
        $request->setCustomerPaymentProfileId($payment_profile_id);
        $controller = new AnetController\GetCustomerPaymentProfileController($request);
        $response = $controller->executeWithApiResponse(env('APP_ENV') == 'production' ? \net\authorize\api\constants\ANetEnvironment::PRODUCTION : \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if ( $response != null && $response->getMessages()->getResultCode() == "Ok" ) {
            $card = $response->getPaymentProfile()->getPayment()->getCreditCard();
            return [
                'payment_profile_id' => $payment_profile_id,
                'cc_type' => $card->getCardType(),
                'cc_last4' => substr($card->getCardNumber(), -4, 4),
            ];
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            fail($errorMessages[0]->getText());
        }
    }

    /**
     * Create a new payment profile for a customer
     *
     * @param $customer_profile_id
     * @param $data
     *
     * @return mixed
     * @throws \AppExcp
     */
    public function createAuthorizenetPaymentProfile($customer_profile_id, $data)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_MERCHANT_TRANSACTION_KEY'));
        // create opaque payment data
        $op = new AnetAPI\OpaqueDataType();
        $op->setDataDescriptor($data['dataDescriptor']);
        $op->setDataValue($data['dataValue']);
        $paymentOne = new AnetAPI\PaymentType();
        $paymentOne->setOpaqueData($op);
        $paymentprofile = new AnetAPI\CustomerPaymentProfileType();
        $paymentprofile->setCustomerType('individual');
        $paymentprofile->setPayment($paymentOne);
        // Assemble the complete transaction request
        $paymentprofilerequest = new AnetAPI\CreateCustomerPaymentProfileRequest();
        $paymentprofilerequest->setMerchantAuthentication($merchantAuthentication);
        $paymentprofilerequest->setCustomerProfileId($customer_profile_id);
        $paymentprofilerequest->setPaymentProfile($paymentprofile);
        // Create the controller and get the response
        $controller = new AnetController\CreateCustomerPaymentProfileController($paymentprofilerequest);
        $response = $controller->executeWithApiResponse(env('APP_ENV') == 'production' ? \net\authorize\api\constants\ANetEnvironment::PRODUCTION : \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if ( $response != null && $response->getMessages()->getResultCode() == "Ok" ) {
            return $response->getCustomerPaymentProfileId();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            fail($errorMessages[0]->getText());
        }

    }


}