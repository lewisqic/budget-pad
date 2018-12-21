<?php

namespace App\Services;

use App\Models\User;
use App\Models\Role;
use Facades\App\Services\ActivityLogService;
use Facades\App\Services\PermissionService;
use Facades\App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Foundation\Auth\ResetsPasswords;

class UserService extends BaseService
{
    use AuthenticatesUsers, SendsPasswordResetEmails, ResetsPasswords {
        ResetsPasswords::credentials insteadof AuthenticatesUsers;
        ResetsPasswords::guard insteadof AuthenticatesUsers;
        ResetsPasswords::redirectPath insteadof AuthenticatesUsers;
        ResetsPasswords::broker insteadof SendsPasswordResetEmails;
    }


    /**
     * @var null
     */
    protected $user = null;


    /**
     * Load an existing user record
     *
     * @param  array  $id
     * @return object
     */
    public function load($id)
    {
        $this->user = User::findOrFail($id);
        return $this;
    }


    /**
     * create a new user record
     *
     * @param  array  $data
     * @return object
     */
    public function create($data)
    {
        $result = \DB::transaction(function() use($data) {
            // get default adminly settings
            $data['adminly_settings'] = User::$adminlyDefaults;
            // create user
            $user = User::create($data);
            // assign to roles
            if ( !empty($data['roles']) ) {
                $user->syncRoles($data['roles']);
            }
            // assign custom permissions
            $user->syncPermissions(PermissionService::getPermissionsFromIds($data['permissions'] ?? []));
            return [
                'user' => $user
            ];
        });
        return $result['user'];
    }


    /**
     * update a user record
     *
     * @param  array  $data
     * @return object
     */
    public function update($data)
    {

        // upload/delete avatar image
        if ( isset($data['delete_avatar']) || isset($data['avatar']) ) {
            $avatar = isset($data['delete_avatar']) ? $this->deleteAvatar($this->user->avatar) : $this->user->avatar;
            $avatar = isset($data['avatar']) ? $this->uploadAvatar('avatar', $data['avatar']) : $avatar;
            $data['avatar'] = $avatar;
        }

        // update user
        $this->user->fill($data)->save();

        // ignore permission/role updates for profile change
        if ( !isset($data['ignore_permissions']) ) {
            // log any role changes
            $roles = $data['roles'] ?? [];
            ActivityLogService::logRelationshipChange('roles', $this->user,
                $this->user->roles->implode('name', ', '),
                RoleService::getRolesFromIds($roles)->implode('name', ', ')
            );
            // update roles
            $this->user->syncRoles($roles);
            // log any permission changes
            $permissions = $data['permissions'] ?? [];
            ActivityLogService::logRelationshipChange('permissions', $this->user,
                $this->user->getDirectPermissions()->implode('label', ', '),
                PermissionService::getPermissionsFromIds($permissions)->implode('label', ', ')
            );
            // update permissions
            $this->user->syncPermissions(PermissionService::getPermissionsFromIds($permissions));
        }

        return $this->user;
    }


    /**
     * return array of user data for datatables
     * @return array
     */
    public function dataTables($data, $type, $company_id = null)
    {
        $trashed = isset($data['with_trashed']) && $data['with_trashed'] == 1 ? true : false;
        $users = User::getByType($type, $trashed, $company_id);
        $users->load('roles');
        $users->load('company');
        $users_arr = [];
        $base = User::$types[\Auth::user()->type]['route'];
        foreach ( $users as $user ) {
            if ( isset($data['role_id']) && !$user->roles->contains('id', $data['role_id']) ) {
                continue;
            }
            $route = $base . ($base == 'admin' ? ($user->company_id ? '/members/' : '/administrators/') : '/users/');
            $users_arr[] = [
                'id' => $user->id,
                'class' => !is_null($user->deleted_at) ? 'text-danger' : null,
                'company' => $user->company->name ?? '',
                'name' => $user->name,
                'first_name' => $user->first_name,
                'last_name' => $user->last_name,
                'email' => $user->email,
                'name_email' => $user->name . ' <small>(' . $user->email . ')</small>',
                'roles' => $user->roles->implode('name', ', '),
                'superuser' => $user->superuser ? 'Yes' : 'No',
                'company_owner' => $user->company_owner ? 'Yes' : 'No',
                'last_login' => [
                    'display' => $user->last_login ? $user->last_login->toFormattedDateString() : '',
                    'sort' => $user->last_login ? $user->last_login->timestamp : ''
                ],
                'created_at' => [
                    'display' => $user->created_at->toFormattedDateString(),
                    'sort' => $user->created_at->timestamp
                ],
                'action' => \Html::dataTablesActionButtons([
                    'click' => $route . $user->id,
                    'edit' => $route . $user->id . '/edit',
                    'delete' => $route . $user->id,
                    'restore' => !is_null($user->deleted_at) ? $route . $user->id : null,
                ])
            ];
        }
        return $users_arr;
    }


    /**
     * Upload an image
     * @param $image
     * @param $data
     *
     * @return null
     * @throws \AppExcp
     */
    public function uploadAvatar($image, $file)
    {
        $filename = null;
        if ( !empty($file) && $file->isValid() ) {
            if ( $file->getClientSize() > 3145728 ) {
                fail('Your ' . $image . ' image is too large.');
            }
            if ( !in_array($file->getMimeType(), ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']) ) {
                fail('You uploaded an unsupported image file');
            }
            $filename = $file->store('avatars', 'public');
        }
        return $filename;
    }

    /**
     * Delete an existing image
     * @param $image
     *
     * @return null
     */
    public function deleteAvatar($image)
    {
        \Storage::disk('public')->delete($image);
        return null;
    }


    /**
     * Handle our successful login event
     *
     * @param Request $request
     * @param         $user
     * @return array
     */
    protected function authenticated(Request $request, $user)
    {
        $route = \Session::has('url.intended') ? \Session::get('url.intended') : User::$types[$user->type]['route'];
        \Session::forget('url.intended');
        return [
            'user' => $user,
            'route' => $route,
        ];

    }


    /**
     * The user has logged out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    protected function loggedOut(Request $request)
    {
        return [
            'message' => trans('auth.logout'),
            'route' => 'auth/login'
        ];
    }


    /**
     * Send the response after sending the password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return array
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return [
            'message' => trans($response),
            'route' => 'auth/login'
        ];
    }


    /**
     * Send the response for a successful password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return array
     */
    protected function sendResetResponse(Request $request, $response)
    {
        $user = User::findByEmail($request->input('email'));
        if ( is_null($user) ) {
            fail('Unable to locate user account');
        }
        return [
            'message' => trans($response),
            'route' => User::$types[$user->type]['route'],
        ];
    }

    
    /**
     * Send the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        fail(trans($response));
    }


    /**
     * Send the response for a failed password reset.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     */
    protected function sendResetFailedResponse(Request $request, $response)
    {
        fail(trans($response));
    }

    /**
     * Load our user settings data into view variables
     */
    public function shareUserData($user = null) {
        if ( $user ) {
            $css_file = 'core.css';
            $color_keys = [];
            if ( isset($user->adminly_settings['colors']) ) {
                foreach ( $user->adminly_settings['colors'] as $style => $color ) {
                    $color_keys[] = str_pad(array_search($color, User::$adminlyColors), 2, '0', STR_PAD_LEFT);
                }
                $css_file .= implode('', $color_keys) . '.css';
                if ( !file_exists(base_path('public/css/' . $css_file)) || $css_file == '010504120913.css' ) {
                    $css_file = 'core.css';
                }
            }
            view()->share('css_file', $css_file);
            view()->share('adminly_settings', $user->adminly_settings);
            view()->share('auth_user', $user);
        }
    }

}