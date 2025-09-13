<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Votes\Models\VoteCategory;

trait HasManyVoteCategory
{
    public function voteCategories(): HasMany
    {
        return $this->hasMany(VoteCategory::class);
    }
}
