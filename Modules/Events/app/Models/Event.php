<?php

declare(strict_types=1);

namespace Modules\Events\Models;

use App\Models\Scopes\TeamScope;
use App\Models\User;
use Core\Traits\BelongsToLocation;
use Core\Traits\FillTeamIdTrait;
use Core\Traits\FillUserIdTrait;
use Goldoni\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Attributes\ScopedBy;
use Illuminate\Database\Eloquent\Casts\AsArrayObject;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Scout\Searchable;
use Modules\Categories\Models\Category;
use Spatie\Image\Enums\Fit;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Tags\HasTags;
use Spatie\Translatable\HasTranslations;

#[ScopedBy([TeamScope::class])]
class Event extends Model implements HasMedia
{
    use SoftDeletes;
    use Searchable;
    use HasTranslations;
    use BelongsToLocation;
    use FillUserIdTrait;
    use FillTeamIdTrait;
    use HasTags;
    use InteractsWithMedia;

    protected $guarded = [];

    public array $translatable = ['description', 'content'];

    protected function casts(): array
    {
        return [
            'description' => AsArrayObject::class,
            'online'      => 'boolean',
            'latitude'    => 'float',
            'longitude'   => 'float',
            'phones'      => AsArrayObject::class,
            'languages'   => AsArrayObject::class,
            'archived_at' => 'datetime',
        ];
    }

    public function searchableAs(): string
    {
        return 'events_index';
    }

    public function toSearchableArray(): array
    {
        return [
            'id'              => $this->id,
            'name'            => (string) $this->name,
            'slug'            => $this->slug,
            'description'     => $this->description,
            'content'         => $this->content,
            'address'         => $this->address,
            'online'          => (bool) $this->online,
            'latitude'        => $this->latitude,
            'longitude'       => $this->longitude,
            'phones'          => $this->phones,
            'seo_title'       => $this->seo_title,
            'seo_description' => $this->seo_description,
            'user_id'         => $this->user_id,
            'team_id'         => $this->team_id,
            'category_id'     => $this->category_id,
            'country_id'      => $this->country_id,
            'division_id'     => $this->division_id,
            'city_id'         => $this->city_id,
            'created_at'      => optional($this->created_at)?->timestamp,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function team(): BelongsTo
    {
        return $this->belongsTo(Team::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('preview')->fit(Fit::Contain)->nonQueued();
        $this->addMediaConversion('thumb')->fit(Fit::Contain)->nonQueued();
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('promo')->singleFile();
        $this->addMediaCollection('files');
        $this->addMediaCollection('images');
    }
}
