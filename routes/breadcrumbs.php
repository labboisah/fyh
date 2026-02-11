<?php

use Diglactic\Breadcrumbs\Breadcrumbs;
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;
use App\Models\Role;
use App\Models\Permission;
use App\Models\User;

// Home / Dashboard
Breadcrumbs::for('dashboard', function (BreadcrumbTrail $trail) {
    $trail->push('Dashboard', route('dashboard'));
});

// Profile
Breadcrumbs::for('profile.edit', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Profile', route('profile.edit'));
});

// Patients
Breadcrumbs::for('patients.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Patients', route('patients.index'));
});

Breadcrumbs::for('patients.create', function (BreadcrumbTrail $trail) {
    $trail->parent('patients.index');
    $trail->push('New Patient', route('patients.create'));
});

// Records
Breadcrumbs::for('records.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Records', route('records.index'));
});

Breadcrumbs::for('records.create', function (BreadcrumbTrail $trail) {
    $trail->parent('records.index');
    $trail->push('New Record', route('records.create'));
});

// Admin Dashboard
Breadcrumbs::for('admin.index', function (BreadcrumbTrail $trail) {
    $trail->parent('dashboard');
    $trail->push('Admin', route('admin.index'));
});

// Admin Roles
Breadcrumbs::for('admin.roles.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Roles', route('admin.roles.index'));
});

Breadcrumbs::for('admin.roles.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.roles.index');
    $trail->push('Create Role', route('admin.roles.create'));
});

Breadcrumbs::for('admin.roles.edit', function (BreadcrumbTrail $trail, Role $role) {
    $trail->parent('admin.roles.index');
    $trail->push("Edit: {$role->name}", route('admin.roles.edit', $role));
});

// Admin Permissions
Breadcrumbs::for('admin.permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Permissions', route('admin.permissions.index'));
});

Breadcrumbs::for('admin.permissions.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.permissions.index');
    $trail->push('Create Permission', route('admin.permissions.create'));
});

Breadcrumbs::for('admin.permissions.edit', function (BreadcrumbTrail $trail, Permission $permission) {
    $trail->parent('admin.permissions.index');
    $trail->push("Edit: {$permission->name}", route('admin.permissions.edit', $permission));
});

// Admin Users
Breadcrumbs::for('admin.users.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Users', route('admin.users.index'));
});

Breadcrumbs::for('admin.users.edit', function (BreadcrumbTrail $trail, User $user) {
    $trail->parent('admin.users.index');
    $trail->push("Manage: {$user->name}", route('admin.users.edit', $user));
});

// Admin Temporary Permissions
Breadcrumbs::for('admin.temporary-permissions.index', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.index');
    $trail->push('Temporary Permissions', route('admin.temporary-permissions.index'));
});

Breadcrumbs::for('admin.temporary-permissions.create', function (BreadcrumbTrail $trail) {
    $trail->parent('admin.temporary-permissions.index');
    $trail->push('Grant Permission', route('admin.temporary-permissions.create'));
});
