<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Concerns\HasExtraUlid;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class WhatsAppMessage extends Model
{
    use HasExtraUlid;

    protected $fillable = [
        'wamid',
        'ulid',
        'recipientId',
        'status',
        'statusAt',
        'phoneNumberId',
        'displayPhoneNumber',
        'conversationId',
        'conversationOrigin',
        'category',
        'billable',
        'pricingModel',
        'sentAt',
        'deliveredAt',
        'readAt',
        'failedAt',
        'raw'
    ];

    protected $casts = [
        'statusAt'    => 'datetime',
        'sentAt'      => 'datetime',
        'deliveredAt' => 'datetime',
        'readAt'      => 'datetime',
        'failedAt'    => 'datetime',
        'billable'    => 'boolean',
        'raw'         => 'array'
    ];
}
