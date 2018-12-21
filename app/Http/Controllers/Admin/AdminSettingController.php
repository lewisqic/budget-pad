<?php

namespace App\Http\Controllers\Admin;

use App\Models\AdminSetting;
use Facades\App\Services\AdminSettingService;
use App\Http\Controllers\Controller;


class AdminSettingController extends Controller
{

    /**
     * Show the login page
     *
     * @return view
     */
    public function index()
    {
        $settings = [];
        foreach ( AdminSetting::all() as $setting ) {
            $setting->value = $setting->type == 'checkbox' ? ($setting->value ? json_decode($setting->value) : []) : $setting->value;
            $settings[$setting->tab][] = $setting;
        }
        $data = [
            'tabs' => AdminSetting::getTabs(),
            'settings' => $settings,
        ];
        return view('content.admin.settings.index', $data);
    }

    /**
     * Save our remark setting
     *
     * @return json
     */
    public function update()
    {
        AdminSettingService::update(\Request::input('settings'));
        \Msg::success('Admin settings have been <strong>updated</strong>');
        return redir('admin/settings');
    }


}
