<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('auth.verify-otp');

    $component->assertSee('');
});
