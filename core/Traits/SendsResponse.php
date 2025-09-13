<?php

namespace Core\Traits;

use Illuminate\Http\JsonResponse;
use JustSteveKing\StatusCode\Http;

/**
 * @property-read mixed $data
 * @property-read Http $status
 */
trait SendsResponse
{
    public function toResponse($request): JsonResponse
    {
        return new JsonResponse(
            data: $this->data,
            status: $this->status->value,
            headers: [],
            options: JSON_PRESERVE_ZERO_FRACTION
        );
    }
}
