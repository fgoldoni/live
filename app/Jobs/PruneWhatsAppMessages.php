<?php


// app/Jobs/PruneWhatsAppMessages.php
declare(strict_types=1);

namespace App\Jobs;

use App\Actions\WhatsApp\PruneMessages;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

final class PruneWhatsAppMessages implements ShouldQueue
{
    use Dispatchable;
    use Queueable;

    public function __construct(
        public ?int $keepLast = null,
        public ?int $olderThanDays = null
    ) {
    }

    public function handle(PruneMessages $pruneMessages): void
    {
        $keep = $this->keepLast ?? (int)config('services.whatsapp.retention.keep_last', 100000);
        $days = $this->olderThanDays ?? (int)config('services.whatsapp.retention.days', 30);
        $pruneMessages->execute($keep, $days);
    }
}
