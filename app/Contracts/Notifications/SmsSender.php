<?php

declare(strict_types=1);

namespace App\Contracts\Notifications;

interface SmsSender
{
    public function send(string $to, string $text): void;
}
