<?php

namespace Core\Traits;

use Illuminate\Support\Facades\Cache;
use Meilisearch\Client;
use Meilisearch\Contracts\SearchQuery;

trait WithMeilisearchTrait
{
    public function getClient(): Client
    {
        return new Client(config('scout.meilisearch.host'), config('scout.meilisearch.key'));
    }

    public function getSearchQuery(
        string $index = '',
        array  $facets = [],
        array  $sort = [],
        string $query = '',
    ): SearchQuery {
        return (new SearchQuery)
            ->setIndexUid($index)
            ->setQuery($query)
            ->setFacets($facets)
            ->setSort($sort);
    }

    public function multiSearch(array $queries = []): array
    {
        return $this->getClient()->multiSearch($queries)['results'];
    }

    public function metaResult(int $limit = 1): array
    {
        return Cache::flexible('meilisearch_result', [5, 10], function () use ($limit) {
            $client = $this->getClient();

            $eventSearchQuery = $this->getSearchQuery(
                'events_index',
                [
                    'city.name',
                    'country.name',
                    'category.name',
                ],
                ['created_at:desc'],
            )->setLimit($limit);

            return $client->multiSearch([
                $eventSearchQuery,
            ])['results'];
        });
    }
}
