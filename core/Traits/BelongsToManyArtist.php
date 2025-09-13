<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Modules\Events\Models\Artist;
use Modules\Events\Models\ArtistEvent;

trait BelongsToManyArtist
{
    public function artists(): BelongsToMany
    {
        return $this->belongsToMany(Artist::class)
            ->using(ArtistEvent::class)
            ->withPivot('id', 'avatar', 'position')
            ->withTimestamps()
            ->orderByPivot('position');
    }
}
