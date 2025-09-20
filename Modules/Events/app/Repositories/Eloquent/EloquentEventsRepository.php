<?php

declare(strict_types=1);

namespace Modules\Events\Repositories\Eloquent;

use Goldoni\CoreRepositories\Repositories\RepositoryAbstract;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Modules\Events\Filters\EventFilter;
use Modules\Events\Models\Event;
use Modules\Events\Repositories\Contracts\EventsRepository;
use Override;

class EloquentEventsRepository extends RepositoryAbstract implements EventsRepository
{
    #[Override]
    public function model(): string
    {
        return Event::class;
    }

    //    public function paginate(EventFilter $filter, array $includes, int $perPage): LengthAwarePaginator
    //    {
    //        $query = $this->baseQuery($filter, $includes);
    //        $this->applySort($query, $filter->sort);
    //        return $query->paginate($perPage)->appends(request()->query());
    //    }

    public function findWithIncludes(int $id, array $includes): ?Event
    {
        return $this->authorizedBase()->with($this->mapIncludes($includes))->withCount([])->find($id);
    }

    public function findTrashed(int $id): ?Event
    {
        return Event::query()->withTrashed()->find($id);
    }

    public function search(?string $query, EventFilter $eventFilter, array $includes, int $page, int $perPage): object
    {
        $builder = $this->authorizedBase()->with($this->mapIncludes($includes));

        if ($eventFilter->teamId) {
            $builder->where('team_id', $eventFilter->teamId);
        }

        if (!is_null($eventFilter->online)) {
            $builder->where('online', $eventFilter->online);
        }

        $lengthAwarePaginator = Event::search($query ?? '')
            ->when($eventFilter->teamId, fn ($s) => $s->where('team_id', $eventFilter->teamId))
            ->when($eventFilter->categoryId, fn ($s) => $s->where('category.id', $eventFilter->categoryId))
            ->when($eventFilter->cityId, fn ($s) => $s->where('city.id', $eventFilter->cityId))
            ->when(!is_null($eventFilter->online), fn ($s) => $s->where('online', $eventFilter->online ? 1 : 0))
            ->paginateRaw(perPage: $perPage, page: $page);
        $records = $builder->whereIn('id', collect($lengthAwarePaginator['hits'])->pluck('id'))->get();
        $ordered = collect($lengthAwarePaginator['hits'])->pluck('id')->map(fn ($id) => $records->firstWhere('id', $id))->filter()->values();

        return (object)['items' => $ordered, 'total' => (int)($lengthAwarePaginator['estimatedTotalHits'] ?? $lengthAwarePaginator['total'] ?? count($ordered)), 'meta' => []];
    }

    public function nearby(float $lat, float $lng, float $radiusKm, EventFilter $eventFilter, array $includes, int $page, int $perPage): object
    {
        $builder   = $this->baseQuery($eventFilter, $includes);
        $haversine = $this->haversineSelect($lat, $lng);
        $builder->select('*')->selectRaw($haversine . ' AS distance_km')
            ->whereNotNull('latitude')->whereNotNull('longitude')
            ->having('distance_km', '<=', $radiusKm)
            ->orderBy('distance_km');
        $total = (clone $builder)->count();
        $items = $builder->forPage($page, $perPage)->get();

        return (object)['items' => $items, 'total' => $total, 'meta' => ['radius_km' => $radiusKm]];
    }

    private function baseQuery(EventFilter $eventFilter, array $includes): Builder
    {
        $builder = $this->authorizedBase()->with($this->mapIncludes($includes));

        if ($eventFilter->teamId) {
            $builder->where('team_id', $eventFilter->teamId);
        }

        if ($eventFilter->categoryId) {
            $builder->where('category_id', $eventFilter->categoryId);
        }

        if ($eventFilter->cityId) {
            $builder->where('city_id', $eventFilter->cityId);
        }

        if (!is_null($eventFilter->online)) {
            $builder->where('online', $eventFilter->online);
        }

        if ($eventFilter->status === 'upcoming') {
            $builder->whereHas('dates', fn ($q) => $q->where('start', '>', now()));
        }

        if ($eventFilter->status === 'passed') {
            $builder->whereDoesntHave('dates', fn ($q) => $q->where('start', '>', now()));
        }

        if ($eventFilter->dateFrom) {
            $builder->whereHas('dates', fn ($q) => $q->where('start', '>=', $eventFilter->dateFrom));
        }

        if ($eventFilter->dateTo) {
            $builder->whereHas('dates', fn ($q) => $q->where('end', '<=', $eventFilter->dateTo));
        }

        return $builder;
    }

    private function authorizedBase(): Builder
    {
        $user  = Auth::user();
        $query = Event::query();

        if (!$user || !$user->hasPermissionTo('nova')) {
            $query->where('online', true);
        }

        if ($user && !$user->isSuperAdmin()) {
            $teamId = $user->currentTeam()?->value('id');

            if ($teamId) {
                $query->where('team_id', $teamId);
            }
        }

        return $query;
    }

    private function mapIncludes(array $includes): array
    {
        $map = [
            'team'     => 'team',
            'category' => 'category',
            'city'     => 'city',
        ];

        return array_values(array_intersect($map, $includes));
    }

    private function haversineSelect(float $lat, float $lng): string
    {
        return '6371 * acos(cos(radians(' . $lat . ')) * cos(radians(latitude)) * cos(radians(longitude) - radians(' . $lng . ')) + sin(radians(' . $lat . ')) * sin(radians(latitude)))';
    }
}
