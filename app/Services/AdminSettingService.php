<?php

namespace App\Services;

use App\Models\AdminSetting;

class AdminSettingService extends BaseService
{

    /**
     * Save an admin setting
     * @param int $user_id
     * @param array $data
     * @return null
     */
    public function update($data) {
        if ( isset($data['checkbox']) ) {
            foreach ( $data['checkbox'] as $checkbox_key ) {
                if ( !isset($data[$checkbox_key]) ) {
                    $data[$checkbox_key] = [];
                }
            }
        }
        foreach ( $data as $key => $value ) {
            $value = is_array($value) ? json_encode($value) : $value;
            $setting = AdminSetting::where('key', $key)->first();
            if ( $setting ) {
                $setting->value = $value;
                $setting->save();
            }
        }
    }


}