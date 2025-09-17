<?php

// app/Events/WhatsApp/MessageStatusChanged.php
declare(strict_types=1);

namespace App\Events\WhatsApp;

use App\Models\WhatsAppMessage;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

final class MessageStatusChanged
{
    use Dispatchable;
    use SerializesModels;

    public function __construct(
        public WhatsAppMessage $message,
        public ?string $oldStatus,
        public string $newStatus
    ) {
    }
}
