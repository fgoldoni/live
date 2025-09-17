<?php

// app/Repositories/EloquentWhatsAppMessageRepository.php
declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\WhatsApp\WhatsAppMessageRepository;
use App\DTO\WhatsApp\MessageStatusDto;
use App\Enums\WhatsAppStatus;
use App\Models\WhatsAppMessage;
use Goldoni\CoreRepositories\Repositories\RepositoryAbstract;

final class EloquentWhatsAppMessageRepository extends RepositoryAbstract implements WhatsAppMessageRepository
{
    public function model(): string
    {
        return WhatsAppMessage::class;
    }

    public function upsertStatus(MessageStatusDto $messageStatusDto): WhatsAppMessage
    {
        $message = WhatsAppMessage::firstOrNew(['wamid' => $messageStatusDto->wamid]);

        $message->fill([
            'recipientId'        => $messageStatusDto->recipientId,
            'status'             => $messageStatusDto->status->value,
            'statusAt'           => $messageStatusDto->occurredAt,
            'phoneNumberId'      => $messageStatusDto->phoneNumberId,
            'displayPhoneNumber' => $messageStatusDto->displayPhoneNumber,
            'conversationId'     => $messageStatusDto->conversationId,
            'conversationOrigin' => $messageStatusDto->conversationOrigin,
            'category'           => $messageStatusDto->category,
            'billable'           => $messageStatusDto->billable,
            'pricingModel'       => $messageStatusDto->pricingModel,
            'raw'                => $messageStatusDto->rawPayload
        ]);

        match ($messageStatusDto->status) {
            WhatsAppStatus::SENT      => $message->sentAt      = $messageStatusDto->occurredAt,
            WhatsAppStatus::DELIVERED => $message->deliveredAt = $messageStatusDto->occurredAt,
            WhatsAppStatus::READ      => $message->readAt      = $messageStatusDto->occurredAt,
            WhatsAppStatus::FAILED    => $message->failedAt    = $messageStatusDto->occurredAt,
            default                   => null
        };

        $message->save();

        return $message;
    }

    public function prune(?int $keepLast = null, ?int $olderThanDays = null): int
    {
        $deleted = 0;

        if ($olderThanDays) {
            $deleted += WhatsAppMessage::query()
                ->where('created_at', '<', now()->subDays($olderThanDays))
                ->delete();
        }

        if ($keepLast) {
            $total  = WhatsAppMessage::query()->count();
            $excess = max(0, $total - $keepLast);

            if ($excess > 0) {
                $ids = WhatsAppMessage::query()
                    ->orderBy('created_at')
                    ->limit($excess)
                    ->pluck('id')
                    ->all();

                if ($ids) {
                    $deleted += WhatsAppMessage::query()->whereIn('id', $ids)->delete();
                }
            }
        }

        return $deleted;
    }
}
