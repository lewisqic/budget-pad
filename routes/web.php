<?php

/**
 * Public site routes
 */
Route::group(['middleware' => ['guest'], 'namespace' => 'Index'], function () {
    Route::get('/', ['uses' => 'IndexIndexController@showHome']);
    Route::get('privacy-policy', ['uses' => 'IndexIndexController@showPrivacyPolicy']);
    Route::get('terms-service', ['uses' => 'IndexIndexController@showTermsService']);
    Route::get('refund-policy', ['uses' => 'IndexIndexController@showRefundPolicy']);
    Route::get('signup/{unique_id?}', ['uses' => 'IndexIndexController@showSignUp']);
    Route::post('signup', ['uses' => 'IndexIndexController@handleSignUp']);
});


/**
 * Auth route group
 */
Route::group(['prefix' => 'auth', 'middleware' => ['guest'], 'namespace' => 'Auth'], function () {
    // GET
    Route::get('/', function() { return redirect('auth/login'); });
    Route::get('login', ['uses' => 'AuthIndexController@showLogin']);
    Route::get('forgot', ['uses' => 'AuthIndexController@showForgot']);
    Route::get('reset/{token}', ['uses' => 'AuthIndexController@showReset'])->name('password.reset');
    // POST
    Route::post('login', ['uses' => 'AuthIndexController@handleLogin']);
    Route::post('forgot', ['uses' => 'AuthIndexController@handleForgot']);
    Route::post('reset', ['uses' => 'AuthIndexController@handleReset']);
});
Route::group(['prefix' => 'auth', 'namespace' => 'Auth'], function () {
    Route::get('logout', ['uses' => 'AuthIndexController@handleLogout']);
});


/**
 * Admin route group
 */
Route::group(['prefix' => 'admin', 'middleware' => ['auth:admin', 'permission'], 'namespace' => 'Admin'], function() {

    // GET
    Route::get('/', ['uses' => 'AdminIndexController@showDashboard']);
    Route::post('save-configurator', 'AdminIndexController@saveConfigurator');
    Route::post('save-favorite', 'AdminIndexController@saveFavorite');
    Route::post('delete-favorite', 'AdminIndexController@deleteFavorite');
    Route::get('search', 'AdminSearchController@showResults');

    // profile
    Route::get('profile', ['uses' => 'AdminProfileController@index']);
    Route::put('profile', ['uses' => 'AdminProfileController@update']);

    // members
    Route::get('members/data', ['uses' => 'AdminMemberController@dataTables']);
    Route::patch('members/{id}', ['uses' => 'AdminMemberController@restore'])->name('admin.members.restore');
    Route::post('members/refund-payment', ['uses' => 'AdminMemberController@refundPayment']);
    Route::resource('members', 'AdminMemberController', ['as' => 'admin']);

    // member roles
    //Route::get('member-roles/data', ['uses' => 'AdminMemberRoleController@dataTables']);
    //Route::patch('member-roles/{id}', ['uses' => 'AdminMemberRoleController@restore'])->name('admin.member-roles.restore');
    //Route::resource('member-roles', 'AdminMemberRoleController', ['as' => 'admin']);

    // administrators
    Route::get('administrators/data', ['uses' => 'AdminAdministratorController@dataTables']);
    Route::patch('administrators/{id}', ['uses' => 'AdminAdministratorController@restore'])->name('admin.administrators.restore');
    Route::resource('administrators', 'AdminAdministratorController', ['as' => 'admin']);

    // administrator roles
    Route::get('administrator-roles/data', ['uses' => 'AdminAdministratorRoleController@dataTables']);
    Route::patch('administrator-roles/{id}', ['uses' => 'AdminAdministratorRoleController@restore'])->name('admin.administrator-roles.restore');
    Route::resource('administrator-roles', 'AdminAdministratorRoleController', ['as' => 'admin']);

    // settings
    Route::get('settings', ['uses' => 'AdminSettingController@index'])->name('admin.settings.index');
    Route::post('settings', ['uses' => 'AdminSettingController@update'])->name('admin.settings.update');

    // activity log
    Route::get('activity/data', ['uses' => 'AdminActivityController@dataTables']);
    Route::resource('activity', 'AdminActivityController', ['as' => 'admin']);

});


/**
 * Account route group
 */
Route::group(['prefix' => 'account', 'middleware' => ['auth:account'], 'namespace' => 'Account'], function() {

    // GET
    Route::get('/', ['uses' => 'AccountIndexController@showDashboard']);
    Route::post('save-configurator', 'AccountIndexController@saveConfigurator');

    // profile
    Route::get('profile', ['uses' => 'AccountProfileController@index']);
    Route::put('profile', ['uses' => 'AccountProfileController@update']);

    // users
    Route::get('users/data', ['uses' => 'AccountUserController@dataTables']);
    Route::patch('users/{id}', ['uses' => 'AccountUserController@restore'])->name('account.users.restore');
    Route::resource('users', 'AccountUserController', ['as' => 'account']);

    // user roles
    Route::get('roles/data', ['uses' => 'AccountRoleController@dataTables']);
    Route::patch('roles/{id}', ['uses' => 'AccountRoleController@restore'])->name('account.roles.restore');
    Route::resource('roles', 'AccountRoleController', ['as' => 'account']);

    // billing
    Route::group(['prefix' => 'billing'], function() {
        Route::get('subscription', ['uses' => 'AccountBillingController@showSubscription']);
        Route::get('payment-methods', ['uses' => 'AccountBillingController@showPaymentMethods']);
        Route::post('payment-method', ['uses' => 'AccountBillingController@handleAddPaymentMethod']);
        Route::put('payment-method/{id}', ['uses' => 'AccountBillingController@handleSetDefaultPaymentMethod']);
        Route::delete('payment-method/{id}', ['uses' => 'AccountBillingController@handleDeletePaymentMethod']);
        Route::get('history', ['uses' => 'AccountBillingController@showBillingHistory']);
        Route::get('history/data', ['uses' => 'AccountBillingController@dataTables']);

        Route::post('cancel-subscription', ['uses' => 'AccountBillingController@handleCancelSubscription']);
        Route::post('resume-subscription', ['uses' => 'AccountBillingController@handleResumeSubscription']);
    });

    // settings
    Route::get('settings', ['uses' => 'AccountSettingController@index'])->name('account.settings.index');
    Route::post('settings', ['uses' => 'AccountSettingController@update'])->name('account.settings.update');

});
