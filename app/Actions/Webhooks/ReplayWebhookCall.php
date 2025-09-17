<?php
// app/Actions/Webhooks/ReplayWebhookCall.php
declare(strict_types=1);

namespace App\Actions\Webhooks;

use App\Jobs\ProcessWhatsAppWebhook;
use Spatie\WebhookClient\Models\WebhookCall;

final readonly class ReplayWebhookCall
{
    public function execute(int $id): void
    {
        $call = WebhookCall::query()->findOrFail($id);
        dispatch(new ProcessWhatsAppWebhook($call));
    }
}
