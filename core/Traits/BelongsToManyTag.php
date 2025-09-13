<?php

namespace Core\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;
use Modules\Tags\App\Models\Tag;

trait BelongsToManyTag
{
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function saveTags($tags): self
    {
        if (count($tags) === 0) {
            return $this;
        }

        $tags = array_filter(
            array_unique(
                array_map(
                    fn ($item) => Str::lower(trim((string) $item)),
                    $tags
                )
            ),
            fn ($item) => ! empty($item)
        );
        $placeholder = implode(', ', array_fill(0, count($tags), '?'));

        $persistedTags = Tag::whereRaw("lower(`name`) in ($placeholder)", $tags)->get();
        $tagsToCreate  = array_diff($tags, $persistedTags->pluck('name')->all());
        $tagsToCreate  = array_map(fn ($tag) => ['name' => $tag, 'slug' => Str::slug($tag)], $tagsToCreate);

        $createdTags   = $this->tags()->createMany($tagsToCreate);
        $persistedTags = $persistedTags->merge($createdTags);
        $this->tags()->sync($persistedTags);

        return $this;
    }
}
