<?php

declare(strict_types=1);

use App\Models\User;

it('base model policy integrates with permissions', function (): void {
    $user = createAdmin();
    $this->actingAs($user);
    expect($user->can('viewAny', User::class))->toBeTrue();
});
