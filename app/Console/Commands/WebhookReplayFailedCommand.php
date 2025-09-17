<?php

// app/Console/Commands/WebhookReplayFailedCommand.php
declare(strict_types=1);

namespace App\Console\Commands;

use App\Jobs\ProcessWhatsAppWebhook;
use Carbon\CarbonImmutable;
use Illuminate\Console\Command;
use Spatie\WebhookClient\Models\WebhookCall;

final class WebhookReplayFailedCommand extends Command
{
    protected $signature   = 'webhook:replay-failed {--since=} {--limit=100} {--name=}';

    protected $description = 'Replays failed stored webhooks';

    public function handle(): int
    {
        $since = $this->option('since') ? CarbonImmutable::parse((string) $this->option('since')) : null;
        $limit = (int) $this->option('limit');
        $name  = $this->option('name');

        $builder = WebhookCall::query()->whereNotNull('exception');

        if ($since instanceof CarbonImmutable) {
            $builder->where('created_at', '>=', $since);
        }

        if ($name) {
            $builder->where('name', (string) $name);
        }

        $calls = $builder->orderBy('created_at')->limit($limit)->get();
        foreach ($calls as $call) {
            dispatch(new ProcessWhatsAppWebhook($call));
        }

        $this->info('Dispatched: ' . $calls->count());

        return self::SUCCESS;
    }
}
