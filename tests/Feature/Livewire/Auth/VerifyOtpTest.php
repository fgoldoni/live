<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

it('can render', function (): void {
    $testable = Volt::test('auth.verify-otp');

    $testable->assertSee('');
});
