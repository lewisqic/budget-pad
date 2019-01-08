<?php

namespace App\Http\Controllers\Index;

use App\Models\User;
use App\Models\Company;
use App\Models\Role;
use Facades\App\Services\CompanyService;
use Facades\App\Services\CompanySubscriptionService;
use Facades\App\Services\UserService;
use App\Http\Controllers\Controller;
//use App\Mail\SignUpConfirmation;

class IndexIndexController extends Controller
{

    /**
     * Show the home page
     *
     * @return view
     */
    public function showHome()
    {
        return view('content.index.index.home');
    }

    /**
     * content page
     * @return view
     */
    public function showPrivacyPolicy()
    {
        $data = [
        ];
        return view('content.index.index.privacy', $data);
    }

    /**
     * content page
     * @return view
     */
    public function showTermsService()
    {
        $data = [
        ];
        return view('content.index.index.terms', $data);
    }

    /**
     * content page
     * @return view
     */
    public function showRefundPolicy()
    {
        $data = [
        ];
        return view('content.index.index.refund', $data);
    }

    /**
     * show our sign up page
     * @return view
     */
    public function showSignUp($id = null)
    {
        $data = [
            'amount' => \Config::get('settings.subscription_amount'),
            'trial' => \Config::get('settings.trial_days'),
        ];
        return view('content.index.index.signup', $data);
    }

    /**
     * handle a member sign up request
     * @return redirect
     */
    public function handleSignUp()
    {
        $data = \Request::all();

        if ( empty($data['token']) ) {
            throw new \AppExcp('We were unable to complete your signup, please refresh the page and try again.');
        }

        $result = \DB::transaction(function() use($data) {

            // create the company
            $company = CompanyService::create($data, $data);

            // create the subscription
            $subscription = CompanySubscriptionService::create([
                'company_id' => $company->id,
                'amount' => \Config::get('settings.subscription_amount'),
                'term' => 'month',
                'status' => 'Trial',
                'next_billing_at' => \Carbon::now()->addDays(\Config::get('settings.trial_days'))
            ]);

            // create the user
            $data['type'] = User::MEMBER_ID;
            $data['company_id'] = $company->id;
            $data['company_owner'] = true;
            $user = UserService::create($data);
            $user->assignRole(Role::where('company_id', $company->id)->first());

            // send confirmation email
            /*$mail_data = [
                'amount' => \Format::currency(\Config::get('settings.subscription_amount')),
                'term' => 'month',
                'next_billing_date' => \Carbon::now()->addDays(\Config::get('settings.trial_days'))->toFormattedDateString()
            ];
            \Mail::to($user->email)->send(new SignUpConfirmation($mail_data));*/

            // find the user and log them in
            \Auth::login($user, true);
            return [
                'route' => url('account')
            ];

        });

        \Msg::success('Thank you! Your subscription is now active.');
        return response()->json(['success' => true, 'route' => $result['route']]);
    }


}
