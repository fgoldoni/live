<?php

declare(strict_types=1);

namespace App\Contracts\Notifications;

/**
 * @phpstan-type TemplateVars     list<string>
 * @phpstan-type TemplateUrlParams list<string>
 */
interface WhatsAppClient
{
    public function sendText(string $phoneE164, string $text): bool;

    /**
     * @param TemplateVars      $vars
     * @param TemplateUrlParams $urlParams
     */
    public function sendTemplate(
        string $to,
        string $templateName,
        array $vars = [],
        array $urlParams = [],
        ?int $ttlSeconds = null,
        string $language = 'fr'
    ): bool;
}
