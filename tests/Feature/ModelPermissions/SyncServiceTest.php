<?php

declare(strict_types=1);

use Goldoni\ModelPermissions\Services\SyncPermissionsService;

it('sync generates and persists permissions', function (): void {
    $syncPermissionsService   = app(SyncPermissionsService::class);
    $models    = (array) config('model-permissions.models', []);
    $abilities = (array) config('model-permissions.abilities', []);
    $guard     = (string) config('model-permissions.guard_name', 'web');
    $globals   = (array) config('model-permissions.global_permissions', []);
    $syncResult    = $syncPermissionsService->sync($models, $abilities, $globals, $guard, false);
    expect($syncResult->created + $syncResult->existing)->toBeGreaterThan(0);
});
