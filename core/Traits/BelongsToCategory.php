<?php

namespace Core\Traits;

use Modules\Categories\Models\Category;

trait BelongsToCategory
{
    public function category(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Category::class)->withDefault();
    }
}
