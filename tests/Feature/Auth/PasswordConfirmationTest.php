<?php

declare(strict_types=1);

use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use Livewire\Volt\Volt;

uses(RefreshDatabase::class);

test('confirm password screen can be rendered', function (): void {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('password.confirm'));

    $response->assertStatus(200);
});

test('password can be confirmed', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $testable = Volt::test('auth.confirm-password')
        ->set('password', 'password')
        ->call('confirmPassword');

    $testable
        ->assertHasNoErrors()
        ->assertRedirect(route('dashboard', absolute: false));
});

test('password is not confirmed with invalid password', function (): void {
    $user = User::factory()->create();

    $this->actingAs($user);

    $testable = Volt::test('auth.confirm-password')
        ->set('password', 'wrong-password')
        ->call('confirmPassword');

    $testable->assertHasErrors(['password']);
});
