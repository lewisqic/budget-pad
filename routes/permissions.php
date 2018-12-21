<?php

return [

    'admin' => [
        [
            'label'      => 'Administrators',
            'controller' => 'AdminAdministratorController',
            'actions'    => [
                'index'           => 'List Administrators',
                'show'            => 'View Administrator',
                'create|store'    => 'Create Administrator',
                'edit|update'     => 'Edit Administrator',
                'destroy|restore' => 'Delete Administrator'
            ]
        ],
        [
            'label'      => 'Roles',
            'controller' => 'AdminRoleController',
            'actions'    => [
                'index'           => 'List Roles',
                'show'            => 'View Role',
                'create|store'    => 'Create Role',
                'edit|update'     => 'Edit Role',
                'destroy|restore' => 'Delete Role'
            ]
        ],
        [
            'label'      => 'Settings',
            'controller' => 'AdminSettingController',
            'actions'    => [
                'index'  => 'View Settings',
                'update' => 'Update Settings',
            ]
        ],
    ],

    'account' => [
        [
            'label'      => 'Users',
            'controller' => 'AccountUserController',
            'actions'    => [
                'index'           => 'List Users',
                'show'            => 'View User',
                'create|store'    => 'Create User',
                'edit|update'     => 'Edit User',
                'destroy|restore' => 'Delete User'
            ]
        ],
        [
            'label'      => 'User Roles',
            'controller' => 'AccountRoleController',
            'actions'    => [
                'index'           => 'List Roles',
                'show'            => 'View Role',
                'create|store'    => 'Create Role',
                'edit|update'     => 'Edit Role',
                'destroy|restore' => 'Delete Role'
            ]
        ],
    ]

];
