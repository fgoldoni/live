<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Brands\Models\Brand;

trait HasManyBrand
{
    public function brands(): HasMany
    {
        return $this->hasMany(Brand::class);
    }
}
