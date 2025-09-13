<?php

declare(strict_types=1);

use App\Mail\Auth\MagicLinkMail;
use Illuminate\Contracts\Queue\ShouldQueue;

it('renders the magic link mail and includes the URL', function (): void {
    $url      = 'https://example.com/magic-login?token=abc';
    $mailable = new MagicLinkMail($url);
    $html     = $mailable->render();
    expect($html)->toContain($url);
});

it('is queued', function (): void {
    expect(is_subclass_of(MagicLinkMail::class, ShouldQueue::class))->toBeTrue();
});
