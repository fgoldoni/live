<?php

declare(strict_types=1);

namespace App\Mail\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class MagicLinkMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    public function __construct(public string $url)
    {
    }

    public function build(): self
    {
        return $this->subject(__('Your magic sign-in link'))
            ->markdown('mail.auth.magic_link', [
                'url' => $this->url,
            ]);
    }
}
