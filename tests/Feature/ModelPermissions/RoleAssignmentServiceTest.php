<?php

declare(strict_types=1);

use Goldoni\ModelPermissions\Services\RoleAssignmentService;

it('assign builds names and assigns to role', function (): void {
    $roleAssignmentService          = app(RoleAssignmentService::class);
    $models           = (array) config('model-permissions.models', []);
    $abilities        = (array) config('model-permissions.abilities', []);
    $guard            = (string) config('model-permissions.guard_name', 'web');
    $globals          = (array) config('model-permissions.global_permissions', []);
    $roleAbility      = (array) config('model-permissions.role_ability_map', []);
    $roleModelAbility = (array) config('model-permissions.role_model_ability_map', []);
    $roleGlobal       = (array) config('model-permissions.role_global_permissions', []);
    $role             = (string) config('model-permissions.super_admin_role', 'Super Admin');
    $names            = $roleAssignmentService->buildPermissionNamesForRole($role, $models, $abilities, $roleAbility, $roleModelAbility, $globals, $roleGlobal);
    $count            = $roleAssignmentService->assign($role, $guard, $names, false, true);
    expect($count)->toBeGreaterThan(0);
});
