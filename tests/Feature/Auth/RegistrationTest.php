<?php

declare(strict_types=1);

test('registration screen can be rendered', function (): void {
    $response = $this->get(route('register'));

    $response->assertStatus(200);
});

it('new users can register', function (): void {
    $response = $this->post(route('register'), [
        'name'                  => 'Test User',
        'email'                 => 'test@example.com',
        'phone'                 => '+491234567890',
        'password'              => 'password',
        'password_confirmation' => 'password',
    ]);

    $response->assertRedirect(route('dashboard'));

    $this->assertDatabaseHas('users', [
        'email' => 'test@example.com',
        'phone' => '+491234567890',
    ]);

    $this->assertAuthenticated();
});
