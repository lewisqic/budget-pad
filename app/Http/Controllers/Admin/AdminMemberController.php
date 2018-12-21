<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Facades\App\Services\UserService;
use Facades\App\Services\CompanyService;
use Facades\App\Services\CompanySubscriptionService;
use Facades\App\Services\CompanyPaymentMethodService;
use Facades\App\Services\CompanyPaymentService;
use App\Http\Controllers\Controller;

use net\authorize\api\contract\v1 as AnetAPI;


class AdminMemberController extends Controller
{

    /**
     * Show the members list page
     *
     * @return view
     */
    public function index()
    {
        return view('content.admin.members.index');
    }

    /**
     * Output our datatabalse json data
     *
     * @return json
     */
    public function dataTables()
    {
        $data = UserService::dataTables(\Request::all(), User::MEMBER_ID, null);
        return response()->json($data);
    }

    /**
     * Show the members create page
     *
     * @return view
     */
    public function create()
    {
        $companies = Company::has('users')->with(['users' => function($query) {
            $query->where('company_owner', true);
        }])->get();
        $data = [
            'title' => 'Add',
            'companies' => $companies
        ];
        return view('content.admin.members.create-edit', $data);
    }

    /**
     * Show the members create page
     *
     * @return view
     */
    public function edit($id)
    {
        $companies = Company::has('users')->with(['users' => function($query) {
            $query->where('company_owner', true);
        }])->get();
        $user = User::findOrFail($id);
        $user->load('company.subscription');
        $data = [
            'title' => 'Edit',
            'user' => $user,
            'company' => $user->company,
            'subscription' => $user->company->subscription,
            'payment_methods' => $user->company->paymentMethods,
            'companies' => $companies
        ];
        return view('content.admin.members.create-edit', $data);
    }

    /**
     * Show an member
     *
     * @return view
     */
    public function show($id)
    {
        $user = User::findOrFail($id);
        $user->load('company.subscription');
        $data = [
            'user' => $user,
            'company' => $user->company,
            'subscription' => $user->company->subscription,
            'payment_methods' => $user->company->paymentMethods,
            'payments' => $user->company->subscription->payments,
        ];
        return view('content.admin.members.show', $data);
    }

    /**
     * Create new member record
     *
     * @return redirect
     */
    public function store()
    {
        $data = \Request::all();
        $user_data = $data['user'];
        $company_data = $data['company'];
        $subscription_data = $data['subscription'];
        $payment_methods_data = $data['payment_methods'];

        if ( $user_data['company_id'] == 'new' ) {
            // create company
            $company = CompanyService::create($company_data, $payment_methods_data);
            $user_data['company_id'] = $company->id;
            $user_data['company_owner'] = true;
            $subscription_data['company_id'] = $company->id;
            // create subscription
            $subscription = CompanySubscriptionService::create($subscription_data);
        }

        // create the user
        $user_data['type'] = User::MEMBER_ID;
        $user = UserService::create($user_data);
        if ( isset($company) ) {
            $user->assignRole(Role::where('company_id', $company->id)->first());
        }

        \Msg::success($user->name . ' has been <strong>added</strong>');
        return redir('admin/members');
    }

    /**
     * Create new member record
     *
     * @return redirect
     */
    public function update()
    {
        $data = \Request::all();
        $user_data = $data['user'];
        $company_data = $data['company'];
        $subscription_data = $data['subscription'];
        $payment_methods_data = $data['payment_methods'];

        // update user
        $user = UserService::load($user_data['id'])->update($user_data);
        $company_old = $user->company;
        // update company
        $company = CompanyService::load($user->company_id)->update($company_data, $payment_methods_data);
        // update subscription
        $subscription = CompanySubscriptionService::load($user->company->subscription->id)->update($subscription_data);
        // update payment methods
        if ( !empty($company_old->customer_profile_id) ) {
            $payment_methods = CompanyPaymentMethodService::updateAll($company, $payment_methods_data);
        }

        \Msg::success($user->name . ' has been <strong>updated</strong>');
        return redir('admin/members');
    }

    /**
     * Delete an member record
     *
     * @return redirect
     */
    public function destroy($id)
    {
        $user = UserService::delete($id);
        \Msg::success($user->name . ' has been <strong>deleted</strong> ' . \Html::undoLink('admin/members/' . $user->id));
        return redir('admin/members');
    }

    /**
     * Restore an member record
     *
     * @return redirect
     */
    public function restore($id)
    {
        $user = UserService::restore($id);
        \Msg::success($user->name . ' has been <strong>restored</strong>');
        return redir('admin/members');
    }

    /**
     * Refund a payment
     * 
     * @return \redirect
     */
    public function refundPayment()
    {
        $result = CompanyPaymentService::refundPayment(\Request::input('id'));
        \Msg::success('Payment has been <strong>refunded</strong>');
        return redir('admin/members/' . \Request::input('user_id') . '#tab=show_payment_history');
    }


}
