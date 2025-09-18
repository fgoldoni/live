<?php

declare(strict_types=1);

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

it('assigns the User role to a user', function () {
    app(PermissionRegistrar::class)->forgetCachedPermissions();

    $guard = config('model-permissions.guard_name', config('auth.defaults.guard', 'web'));

    $role = Role::findOrCreate('User', $guard);

    $user = User::factory()->create();

    $user->assignRole($role);

    expect($user->hasRole('User'))->toBeTrue()
        ->and($user->getRoleNames()->toArray())->toContain('User');
});
