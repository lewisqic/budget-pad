<?php
namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\CompanySubscription;
use App\Models\CompanyPayment;
use App\Http\Controllers\Controller;

class AdminIndexController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show our dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDashboard()
    {
        $all = CompanySubscription::all();
        $days = [];
        foreach ( $all as $sub ) {
            $end = $sub->canceled_at ? $sub->canceled_at : \Carbon::now();
            $days[] = $sub->created_at->diffInDays($end);
        }
        $avg = count($days) ? round(array_sum($days) / count($days)) : 0;

        $subscriptions = CompanySubscription::select(\DB::raw('count(*) as count, status'))->groupBy('status')->get();
        $subscribers = [
            'Active' => 0,
            'Canceled' => 0,
            'Pending Cancelation' => 0,
        ];
        foreach ( $subscriptions as $group ) {
            if ( isset($subscribers[$group['status']]) ) {
                $subscribers[$group['status']] = $group['count'];
            }
        }


        $active_revenue = [];
        foreach ( $all as $sub ) {
            if ( $sub->status == 'Active' ) {
                $active_revenue[] = $sub->term == 'month' ? $sub->amount : $sub->amount / 12;
            }
        }
        $payments = CompanyPayment::where('status', 'Complete')->whereBetween('created_at', [date('Y-01-01 00:00:00'), date('Y-m-d 23:59:59')])->get();
        $monthly = round(array_sum($active_revenue) / count($active_revenue));
        $revenue = [
            'monthly' => $monthly,
            'yearly' => $monthly * 12,
            'year_to_date' => $payments->sum('amount'),
        ];

        $data = [
            'subscribers' => $subscribers,
            'average_days' => $avg,
            'revenue' => $revenue
        ];
        return view('content.admin.index.dashboard', $data);
    }


    /**
     * Save our adminly configurator settings
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveConfigurator()
    {
        $user = User::find(\Auth::user()->id);
        $user->adminly_settings = \Request::input('adminly_settings');
        $user->save();

        $colors = implode(',', array_values($user->adminly_settings['colors']));

        // determine the filename based on our selected colors and their index position
        $filename = '';
        $color_keys = [];
        foreach ( $user->adminly_settings['colors'] as $style => $color ) {
            $color_keys[] = str_pad(array_search($color, User::$adminlyColors), 2, '0', STR_PAD_LEFT);
        }
        $filename .= implode('', $color_keys) . '.css';

        $return_var = 0;
        if ( !file_exists(base_path('public/css/' . $filename)) ) {

            // production
            //exec('cd ' . base_path() . ' && node node_modules/cross-env/dist/bin/cross-env.js NODE_ENV=production node_modules/webpack/bin/webpack.js --no-progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js --env.colors=' . $colors . ' --env.filename=' . $filename . ' 2>&1', $output, $return_var);

            // development
            exec('cd ' . base_path() . ' && node node_modules/cross-env/dist/bin/cross-env.js NODE_ENV=development node_modules/webpack/bin/webpack.js --progress --hide-modules --config=node_modules/laravel-mix/setup/webpack.config.js --env.colors=' . $colors . ' --env.filename=' . $filename . ' 2>&1', $output, $return_var);

        } else {
            if ( $filename == '010504120913.css' ) {
                $filename = 'core.css';
            }
        }

        return response()->json(['success' => $return_var === 0 ? true : false, 'filename' => $filename, 'settings' => $user->adminly_settings]);

    }

    /**
     * Save our adminly favorite page
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function saveFavorite()
    {
        $user = User::find(\Auth::user()->id);
        $settings = $user->adminly_settings;
        $favorites = isset($settings['favorites']) && is_array($settings['favorites']) ? $settings['favorites'] : [];
        $favorites[] = \Request::only('icon', 'title', 'path');
        array_set($settings, 'favorites', $favorites);
        $user->adminly_settings = $settings;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Page added to favorites list']);
    }

    /**
     * Delete favorite page 
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function deleteFavorite()
    {
        $path = \Request::input('path');
        $user = User::find(\Auth::user()->id);
        $settings = $user->adminly_settings;
        $favorites = $settings['favorites'];
        foreach ( $favorites as $index => $fav ) {
            if ( $fav['path'] == $path ) {
                unset($favorites[$index]);
            }
        }
        array_set($settings, 'favorites', $favorites);
        $user->adminly_settings = $settings;
        $user->save();
        return response()->json(['success' => true, 'message' => 'Page removed from favorites list']);
    }

}