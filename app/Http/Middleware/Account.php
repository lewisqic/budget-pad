<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;

class Account
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = \Auth::user();
        $user->load('company.subscription');
        $company = $user->company;
        $subscription = $company->subscription;

        view()->share('company', $company);
        view()->share('subscription', $subscription);

        app()->singleton('subscription', function($app) use($subscription) {
            return $subscription;
        });
        app()->singleton('company', function($app) use($company) {
            return $company;
        });

        // check for canceled subscription, redirect if needed
        if ( ($subscription->status == 'Canceled') && !preg_match('/^(account\/billing|account\/profile)/', $request->path()) ) {
            return redir('account/billing/subscription');
        }

        return $next($request);
    }
}
