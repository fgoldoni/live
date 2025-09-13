<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Categories\Models\Category;

trait BelongsToManyCategory
{
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class);
    }
}
