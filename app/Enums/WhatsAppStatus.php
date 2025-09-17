<?php

// app/Enums/WhatsAppStatus.php
declare(strict_types=1);

namespace App\Enums;

enum WhatsAppStatus: string
{
    case ACCEPTED  = 'accepted';
    case SENT      = 'sent';
    case DELIVERED = 'delivered';
    case READ      = 'read';
    case FAILED    = 'failed';
}
