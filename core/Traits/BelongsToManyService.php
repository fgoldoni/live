<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Models\Models\ModelService;
use Modules\Models\Models\Service;

trait BelongsToManyService
{
    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)
            ->using(ModelService::class)
            ->withPivot('id', 'quantity', 'position')
            ->withTimestamps();
    }
}
