<?php

namespace Core\Responsable;

use Core\Traits\SendsResponse;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use JustSteveKing\StatusCode\Http;
use Meilisearch\Search\SearchResult;

class JsonResponse implements Responsable
{
    use SendsResponse;

    public function __construct(
        public readonly array|LengthAwarePaginator|Model|SearchResult $data,
        public readonly Http $status = Http::OK,
    ) {
    }
}
