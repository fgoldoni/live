<?php

declare(strict_types=1);

namespace Modules\Events\Repositories\Contracts;

use Modules\Events\Filters\EventFilter;
use Modules\Events\Models\Event;

interface EventsRepository
{
    //    public function paginate(EventFilter $filter, array $includes, int $perPage): LengthAwarePaginator;
    public function findWithIncludes(int $id, array $includes): ?Event;

    public function findTrashed(int $id): ?Event;

    public function search(?string $query, EventFilter $eventFilter, array $includes, int $page, int $perPage): object;

    public function nearby(float $lat, float $lng, float $radiusKm, EventFilter $eventFilter, array $includes, int $page, int $perPage): object;
}
