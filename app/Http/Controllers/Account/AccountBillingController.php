<?php

namespace App\Http\Controllers\Account;

use App\Models\User;
use Facades\App\Services\CompanySubscriptionService;
use Facades\App\Services\CompanyPaymentMethodService;
use Facades\App\Services\CompanyPaymentService;
use App\Http\Controllers\Controller;

class AccountBillingController extends Controller {

    /**
     * show our subscription details page
     *
     * @return view
     */
    public function showSubscription()
    {
        return view('content.account.billing.subscription');
    }

    /**
     * show our payment method page
     *
     * @return view
     */
    public function showPaymentMethods()
    {
        $payment_methods = app('company')->paymentMethods;
        $data = [
            'payment_methods' => $payment_methods,
        ];
        return view('content.account.billing.payment-methods', $data);
    }

    /**
     * show our the billing history page
     *
     * @return view
     */
    public function showBillingHistory()
    {
        $data = [
            'payments' => app('subscription')->payments,
        ];
        return view('content.account.billing.history', $data);
    }

    /**
     * handle our cancel subscription request
     *
     * @return redirect
     */
    public function handleCancelSubscription()
    {
        $response = CompanySubscriptionService::load(app('subscription')->id)->update([
            'status' => 'Pending Cancelation',
        ]);
        \Msg::success('Your subscription has been canceled.');
        return redir('account/billing/subscription');
    }

    /**
     * handle our resume subscription request
     *
     * @return redirect
     */
    public function handleResumeSubscription()
    {
        $response = CompanySubscriptionService::load(app('subscription')->id)->update([
            'status' => 'Active',
            'canceled_at' => null,
        ]);
        \Msg::success('Your subscription has been resumed.');
        return redir('account/billing/subscription');
    }

    /**
     * handle our plan change request
     *
     * @return json
     */
    public function handleAddPaymentMethod()
    {
        $response = CompanyPaymentMethodService::addSource(app('company'), \Request::input('token'));
        \Msg::success('Payment method has been added successfully!');
        return redir('account/billing/payment-methods');
    }

    /**
     * handle our delete payment method request
     *
     * @param int $id
     * @return redirect
     */
    public function handleDeletePaymentMethod($id)
    {
        $response = CompanyPaymentMethodService::delete($id);
        \Msg::success('Payment method has been deleted.');
        return redir('account/billing/payment-methods');
    }

    /**
     * handle our set defautl payment method request
     *
     * @param int $id
     * @return redirect
     */
    public function handleSetDefaultPaymentMethod()
    {
        $response = CompanyPaymentMethodService::setDefault(app('company'), \Request::input('payment_method_id'));
        \Msg::success('Default payment method has been updated.');
        return redir('account/billing/payment-methods');
    }

}
