<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('otp.verify-phone');

    $component->assertSee('');
});
