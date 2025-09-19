<?php

declare(strict_types=1);

use App\Models\User;

it('can render', function (): void {
    $user = User::factory()->create();
    $this->actingAs($user);

    $this->get(route('otp.verify'))->assertOk();
});
