<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Teams\Models\Social;

trait HasOneSocial
{
    public function social(): HasOne
    {
        return $this->hasOne(Social::class)->withDefault();
    }
}
