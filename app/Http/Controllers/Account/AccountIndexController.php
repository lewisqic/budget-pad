<?php
namespace App\Http\Controllers\Account;

use App\Models\User;
use App\Models\Expense;
use Facades\App\Services\IncomeService;
use Facades\App\Services\ExpenseService;
use Facades\App\Services\CategoryService;
use Facades\App\Services\TagService;
use App\Mail\AdminFeedback;
use App\Http\Controllers\Controller;

class AccountIndexController extends Controller
{


    /**
     * Show our dashboard page
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showDashboard()
    {
        if ( session('start_date') && session('end_date') ) {
            $dates = [\Carbon::parse(session('start_date'))->format('Y-m-d'), \Carbon::parse(session('end_date'))->format('Y-m-d')];
        } else {
            $dates = [\Carbon::now()->startOfMonth()->format('Y-m-d'), \Carbon::now()->endOfMonth()->format('Y-m-d')];
        }
        $data = [
            'dates' => $dates,
            'chart_data' => ExpenseService::getChartData($dates),
            'tile_data' => ExpenseService::getDashboardTiles($dates),
            'category_data' => CategoryService::getDashboardStats($dates),
            'tag_data' => TagService::getDashboardStats($dates),
        ];
        return view('content.account.index.dashboard', $data);
    }

    public function changeDates()
    {
        $dates = [
            \Carbon::parse(\Request::input('start_date'))->format('Y-m-d'),
            \Carbon::parse(\Request::input('end_date'))->format('Y-m-d'),
        ];
        session(['start_date' => $dates[0], 'end_date' => $dates[1]]);
        return back();
    }

    public function sendFeedback()
    {
        $data = \Request::all();
        $user = \Auth::user();
        $mail_data = [
            'replyTo' => [
                ['name' => $user->name, 'address' => $user->email]
            ],
            'user' => $user,
            'message' => $data['message']
        ];
        \Mail::to(\Config::get('settings.system_email'))->send(new AdminFeedback($mail_data));

        return response()->json(['success' => true, 'message' => 'Your message has been sent to an administrator, we\'ll be in touch soon!']);
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