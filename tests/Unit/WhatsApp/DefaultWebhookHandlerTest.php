<?php

declare(strict_types=1);

use App\Actions\WhatsApp\UpsertMessageStatus;
use App\Services\WhatsApp\DefaultWebhookHandler;
use Mockery as m;

it('converts webhook payload to DTOs and calls the action', function (): void {
    $payload = [
        'entry' => [[
            'changes' => [[
                'value' => [
                    'metadata' => [
                        'phone_number_id'      => '12345',
                        'display_phone_number' => '+1 202 555-0187',
                    ],
                    'statuses' => [
                        [
                            'id'           => 'wamid.ABC',
                            'recipient_id' => '4917647',
                            'status'       => 'delivered',
                            'timestamp'    => (string) now()->timestamp,
                            'conversation' => ['id' => 'conv1', 'origin' => ['type' => 'user_initiated']],
                            'pricing'      => ['category' => 'utility', 'billable' => true, 'pricing_model' => 'CBP'],
                        ],
                        [
                            'id'           => 'wamid.DEF',
                            'recipient_id' => '4917000',
                            'status'       => 'read',
                            'timestamp'    => (string) now()->timestamp,
                        ],
                    ],
                ],
            ]],
        ]],
    ];

    $mock = m::mock(UpsertMessageStatus::class);
    $mock->shouldReceive('execute')->twice()->with(m::on(fn ($dto): bool => $dto->wamid !== '' && in_array($dto->status->value, ['delivered', 'read'], true)));

    $handler = new DefaultWebhookHandler($mock);
    $handler->handle($payload);
});
