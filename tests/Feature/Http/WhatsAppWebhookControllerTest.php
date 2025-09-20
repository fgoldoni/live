<?php

declare(strict_types=1);

it('verifies webhook with correct token and echoes challenge', function (): void {
    config()->set('services.whatsapp.webhook_verify_token', 'secret-verify');

    $res = $this->get('/webhooks/whatsapp?hub.mode=subscribe&hub.verify_token=secret-verify&hub.challenge=abc123');

    $res->assertOk()
        ->assertSee('abc123', false);

    expect($res->headers->get('Content-Type'))->toStartWith('text/plain');
});

it('rejects webhook when token mismatch', function (): void {
    config()->set('services.whatsapp.webhook_verify_token', 'secret-verify');

    $res = $this->get('/webhooks/whatsapp?hub.mode=subscribe&hub.verify_token=wrong&hub.challenge=abc123');

    $res->assertStatus(403);
});
