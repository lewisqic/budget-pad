<?php

namespace App\Services;

use App\Models\CompanyPayment;
use net\authorize\api\contract\v1 as AnetAPI;
use net\authorize\api\controller as AnetController;

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
     * Charge a customer profile
     *
     * @param $company
     * @param $payment_method
     * @param $amount
     *
     * @throws \AppExcp
     */
    public function chargeCustomer($company, $payment_method, $amount)
    {
        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_MERCHANT_TRANSACTION_KEY'));
        $profileToCharge = new AnetAPI\CustomerProfilePaymentType();
        $profileToCharge->setCustomerProfileId($company->customer_profile_id);
        $paymentProfile = new AnetAPI\PaymentProfileType();
        $paymentProfile->setPaymentProfileId($payment_method->payment_profile_id);
        $profileToCharge->setPaymentProfile($paymentProfile);
        $transactionRequestType = new AnetAPI\TransactionRequestType();
        $transactionRequestType->setTransactionType('authCaptureTransaction');
        $transactionRequestType->setAmount($amount);
        $transactionRequestType->setProfile($profileToCharge);
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId('ref' . time());
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse(env('APP_ENV') == 'production' ? \net\authorize\api\constants\ANetEnvironment::PRODUCTION : \net\authorize\api\constants\ANetEnvironment::SANDBOX);
        if ( $response != null && $response->getMessages()->getResultCode() == "Ok" ) {
            $tresponse = $response->getTransactionResponse();
            if ( $tresponse != null && $tresponse->getMessages() != null ) {
                // create payment record
                $this->create([
                    'company_id'                => $company->id,
                    'company_subscription_id'   => $company->subscription->id,
                    'company_payment_method_id' => $payment_method->id,
                    'transaction_id'            => $tresponse->getTransId(),
                    'amount'                    => $amount,
                    'notes'                     => ucwords($company->subscription->term . 'ly subscription fee'),
                    'status'                    => 'Complete'
                ]);
                // return transaction id
                return $tresponse->getTransId();
            } else {
                if ( $tresponse->getErrors() != null ) {
                    $error = $tresponse->getErrors()[0]->getErrorText();
                }
            }
        } else {
            $tresponse = $response->getTransactionResponse();
            if ( $tresponse != null && $tresponse->getErrors() != null ) {
                $error = $tresponse->getErrors()[0]->getErrorText();
            } else {
                $error = $response->getMessages()->getMessage()[0]->getText();
            }
        }
        fail($error ?? 'Unknown payment error');
    }

    /**
     * Refund a payment
     *
     * @param $id
     *
     * @throws \AppExcp
     */
    public function refundPayment($id)
    {
        $payment = CompanyPayment::findOrFail($id);

        $merchantAuthentication = new AnetAPI\MerchantAuthenticationType();
        $merchantAuthentication->setName(env('AUTHORIZENET_MERCHANT_LOGIN_ID'));
        $merchantAuthentication->setTransactionKey(env('AUTHORIZENET_MERCHANT_TRANSACTION_KEY'));
        $env = env('APP_ENV') == 'production' ? \net\authorize\api\constants\ANetEnvironment::PRODUCTION : \net\authorize\api\constants\ANetEnvironment::SANDBOX;

        // get transaction details
        $request = new AnetAPI\GetTransactionDetailsRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setTransId($payment->transaction_id);
        $controller = new AnetController\GetTransactionDetailsController($request);
        $response = $controller->executeWithApiResponse($env);
        if ( $response != null && $response->getMessages()->getResultCode() == "Ok" ) {
            $transaction_status = $response->getTransaction()->getTransactionStatus();
        } else {
            $errorMessages = $response->getMessages()->getMessage();
            fail($errorMessages[0]->getText());
        }

        if ( in_array($transaction_status, ['authorizedPendingCapture', 'capturedPendingSettlement']) ) {
            // void
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("voidTransaction");
            $transactionRequestType->setRefTransId($payment->transaction_id);
        } else {
            // refund
            $creditCard = new AnetAPI\CreditCardType();
            $creditCard->setCardNumber($payment->paymentMethod->cc_last4);
            $creditCard->setExpirationDate('XXXX');
            $paymentOne = new AnetAPI\PaymentType();
            $paymentOne->setCreditCard($creditCard);
            $transactionRequestType = new AnetAPI\TransactionRequestType();
            $transactionRequestType->setTransactionType("refundTransaction");
            $transactionRequestType->setAmount($payment->amount);
            $transactionRequestType->setPayment($paymentOne);
            $transactionRequestType->setRefTransId($payment->transaction_id);
        }
        $request = new AnetAPI\CreateTransactionRequest();
        $request->setMerchantAuthentication($merchantAuthentication);
        $request->setRefId('ref' . time());
        $request->setTransactionRequest($transactionRequestType);
        $controller = new AnetController\CreateTransactionController($request);
        $response = $controller->executeWithApiResponse($env);
        if ( $response != null && $response->getMessages()->getResultCode() == "Ok" ) {
            $tresponse = $response->getTransactionResponse();
            if ( $tresponse != null && $tresponse->getMessages() != null ) {
                // update payment record
                $this->load($id)->update([
                    'refund_id'   => $tresponse->getTransId(),
                    'status'      => 'Refunded',
                    'refunded_at' => time(),
                ]);
                // return transaction id
                return $tresponse->getTransId();
            } else {
                if ( $tresponse->getErrors() != null ) {
                    $error = $tresponse->getErrors()[0]->getErrorText();
                }
            }
        } else {
            $tresponse = $response->getTransactionResponse();
            if ( $tresponse != null && $tresponse->getErrors() != null ) {
                $error = $tresponse->getErrors()[0]->getErrorText();
            } else {
                $error = $response->getMessages()->getMessage()[0]->getText();
            }
        }
        fail($error ?? 'Unknown refund error');

    }


}