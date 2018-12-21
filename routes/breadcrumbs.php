<?php

/**
 * ADMIN breadcrumbs
 */

// dashboard
\Breadcrumbs::for('admin', function($trail)
{
    $trail->push('Dashboard', url('admin'));
});

// search
\Breadcrumbs::for('admin/search', function($trail)
{
    $trail->parent('admin');
    $trail->push('Search Results', url('admin/search'));
});

// profile
\Breadcrumbs::for('admin/profile', function($trail)
{
    $trail->parent('admin');
    $trail->push('Profile', url('admin/profile'));
});

// members
\Breadcrumbs::for('admin/members', function($trail)
{
    $trail->parent('admin');
    $trail->push('Members', url('admin/members'));
});
\Breadcrumbs::for('admin/members/create', function($trail)
{
    $trail->parent('admin/members');
    $trail->push('Create', url('admin/members/create'));
});
\Breadcrumbs::for('admin/members/show', function($trail, $user)
{
    $trail->parent('admin/members');
    $trail->push($user->name, url('admin/members/' . $user->id));
});
\Breadcrumbs::for('admin/members/edit', function($trail, $user)
{
    $trail->parent('admin/members/show', $user);
    $trail->push('Edit', url('admin/members/edit/' . $user->id));
});

// member roles
\Breadcrumbs::for('admin/member-roles', function($trail)
{
    $trail->parent('admin');
    $trail->push('Member Roles', url('admin/member-roles'));
});
\Breadcrumbs::for('admin/member-roles/create', function($trail)
{
    $trail->parent('admin/member-roles');
    $trail->push('Create', url('admin/member-roles/create'));
});
\Breadcrumbs::for('admin/member-roles/show', function($trail, $role)
{
    $trail->parent('admin/member-roles');
    $trail->push($role->name, url('admin/member-roles/' . $role->id));
});
\Breadcrumbs::for('admin/member-roles/edit', function($trail, $role)
{
    $trail->parent('admin/member-roles/show', $role);
    $trail->push('Edit', url('admin/member-roles/edit/' . $role->id));
});

// administrators
\Breadcrumbs::for('admin/administrators', function($trail)
{
    $trail->parent('admin');
    $trail->push('Administrators', url('admin/administrators'));
});
\Breadcrumbs::for('admin/administrators/create', function($trail)
{
    $trail->parent('admin/administrators');
    $trail->push('Create', url('admin/administrators/create'));
});
\Breadcrumbs::for('admin/administrators/show', function($trail, $user)
{
    $trail->parent('admin/administrators');
    $trail->push($user->name, url('admin/administrators/' . $user->id));
});
\Breadcrumbs::for('admin/administrators/edit', function($trail, $user)
{
    $trail->parent('admin/administrators/show', $user);
    $trail->push('Edit', url('admin/administrators/edit/' . $user->id));
});

// administrator roles
\Breadcrumbs::for('admin/administrator-roles', function($trail)
{
    $trail->parent('admin');
    $trail->push('Administrator Roles', url('admin/administrator-roles'));
});
\Breadcrumbs::for('admin/administrator-roles/create', function($trail)
{
    $trail->parent('admin/administrator-roles');
    $trail->push('Create', url('admin/administrator-roles/create'));
});
\Breadcrumbs::for('admin/administrator-roles/show', function($trail, $role)
{
    $trail->parent('admin/administrator-roles');
    $trail->push($role->name, url('admin/administrator-roles/' . $role->id));
});
\Breadcrumbs::for('admin/administrator-roles/edit', function($trail, $role)
{
    $trail->parent('admin/administrator-roles/show', $role);
    $trail->push('Edit', url('admin/administrator-roles/edit/' . $role->id));
});

// settings
\Breadcrumbs::for('admin/settings', function($trail)
{
    $trail->parent('admin');
    $trail->push('Settings', url('admin/settings'));
});

// activity log
\Breadcrumbs::for('admin/activity', function($trail)
{
    $trail->parent('admin');
    $trail->push('Activity Logs', url('admin/activity'));
});


/**
 * ACCOUNT breadcrumbs
 */

// dashboard
\Breadcrumbs::for('account', function($trail)
{
    $trail->push('Dashboard', url('account'));
});

// profile
\Breadcrumbs::for('account/profile', function($trail)
{
    $trail->parent('account');
    $trail->push('Profile', url('account/profile'));
});

// billing
\Breadcrumbs::for('account/billing/subscription', function($trail)
{
    $trail->parent('account');
    $trail->push('My Subscription', url('account/billing/subscription'));
});
\Breadcrumbs::for('account/billing/upgrade', function($trail)
{
    $trail->parent('account');
    $trail->push('Complete Subscription', url('account/billing/upgrade'));
});
\Breadcrumbs::for('account/billing/payment-methods', function($trail)
{
    $trail->parent('account');
    $trail->push('Payment Methods', url('account/billing/payment-methods'));
});
\Breadcrumbs::for('account/billing/history', function($trail)
{
    $trail->parent('account');
    $trail->push('Billing History', url('account/billing/history'));
});
\Breadcrumbs::for('account/billing/change-plan', function($trail)
{
    $trail->parent('account/billing/subscription');
    $trail->push('Change Subscription Plan', url('account/billing/change-plan'));
});

// settings
\Breadcrumbs::for('account/settings', function($trail)
{
    $trail->parent('account');
    $trail->push('Settings', url('account/settings'));
});

// users
\Breadcrumbs::for('account/users', function($trail)
{
    $trail->parent('account');
    $trail->push('Users', url('account/users'));
});
\Breadcrumbs::for('account/users/create', function($trail)
{
    $trail->parent('account/users');
    $trail->push('Create', url('account/users/create'));
});
\Breadcrumbs::for('account/users/show', function($trail, $user)
{
    $trail->parent('account/users');
    $trail->push($user->name, url('account/users/' . $user->id));
});
\Breadcrumbs::for('account/users/edit', function($trail, $user)
{
    $trail->parent('account/users/show', $user);
    $trail->push('Edit', url('account/users/edit/' . $user->id));
});

// user roles
\Breadcrumbs::for('account/roles', function($trail)
{
    $trail->parent('account');
    $trail->push('User Roles', url('account/roles'));
});
\Breadcrumbs::for('account/roles/create', function($trail)
{
    $trail->parent('account/roles');
    $trail->push('Create', url('account/roles/create'));
});
\Breadcrumbs::for('account/roles/show', function($trail, $role)
{
    $trail->parent('account/roles');
    $trail->push($role->name, url('account/roles/' . $role->id));
});
\Breadcrumbs::for('account/roles/edit', function($trail, $role)
{
    $trail->parent('account/roles/show', $role);
    $trail->push('Edit', url('account/roles/edit/' . $role->id));
});