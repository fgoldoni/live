<?php

// app/Console/Commands/WebhookReplayCommand.php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Actions\Webhooks\ReplayWebhookCall;
use Illuminate\Console\Command;

final class WebhookReplayCommand extends Command
{
    protected $signature   = 'webhook:replay {id : WebhookCall id}';

    protected $description = 'Replays a stored webhook by id';

    public function handle(ReplayWebhookCall $replayWebhookCall): int
    {
        $replayWebhookCall->execute((int) $this->argument('id'));
        $this->info('Dispatched');

        return self::SUCCESS;
    }
}
