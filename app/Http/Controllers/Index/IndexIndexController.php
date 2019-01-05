<?php

namespace App\Http\Controllers\Index;

use App\Models\Company;
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
        $page = !is_null($id) ? SignupPage::where('unique_id', $id)->first() : SignupPage::where('is_default', true)->first();
        $data = [
            'page' => $page
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

            $data['type'] = Member::USER_TYPE_ID;
            // honeypot checking for valid submission
            $this->companyService->honeypotCheck($data);

            // get our signup page
            $page = SignupPage::where('id', $data['signup_page'])->first();
            if ( is_null($page) ) {
                throw new \AppExcp('We were unable to complete your signup, please try again.');
            }

            // create the company
            list($company, $role) = $this->companyService->create($data);

            // create the user
            $data['company_id'] = $company->id;
            $data['roles'] = [$role->id];
            $data['is_owner'] = true;
            $user = $this->userService->create($data);

            // update subscription for paying customer
            $subscription_result = $this->companySubscriptionService->upgrade([
                'company' => $company,
                'user' => $user,
                'amount' => $page->amount,
                'term' => $page->term,
                'token' => $data['token']
            ]);

            // send confirmation email
            $mail_data = [
                'amount' => \Format::currency($page->amount),
                'term' => $page->term,
                'next_billing_date' => $page->term == 'year' ? \Carbon::now()->addYear()->toFormattedDateString() : \Carbon::now()->addMonth()->toFormattedDateString()
            ];
            \Mail::to($user->email)->send(new SignUpConfirmation($mail_data));

            // find the user and log them in
            $auth_user = \Auth::findById($user->id);
            \Auth::login($auth_user, true);
            return [
                'route' => url('account')
            ];

        });

        \Msg::success('Thank you! Your payment has been processed and your subscription is now active. Enjoy!');
        return response()->json(['success' => true, 'route' => $result['route']]);
    }


}
