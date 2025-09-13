<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\MorphMany;
use Modules\Reports\Models\Report;

trait MorphManyReport
{
    public function reports(): MorphMany
    {
        return $this->morphMany(Report::class, 'reportable');
    }
}
