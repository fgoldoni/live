<?php

use Livewire\Volt\Volt;

it('can render', function () {
    $component = Volt::test('opt.verify-phone');

    $component->assertSee('');
});
