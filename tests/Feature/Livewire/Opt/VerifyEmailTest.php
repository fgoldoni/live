<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('opt.verify-email');

    $component->assertSee('');
});
