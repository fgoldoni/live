<?php

declare(strict_types=1);

use Livewire\Volt\Volt;

it('can render', function (): void {
    $testable = Volt::test('otp.verify');

    $testable->assertSee('');
});
