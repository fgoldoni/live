<?php

declare(strict_types=1);

namespace Modules\Events\Transformers;

use Illuminate\Http\Resources\Json\JsonResource;
use Override;

class EventResource extends JsonResource
{
    private array $only = [];

    private array $includes = [];

    public function fields(array $fields): self
    {
        $this->only = $fields;

        return $this;
    }

    public function includes(array $includes): self
    {
        $this->includes = $includes;

        return $this;
    }

    #[Override]
    public function toArray($request): array
    {
        return [
            'type'       => 'events',
            'id'         => (string) $this->id,
            'attributes' => $this->filterFields([
                'name'                       => $this->getTranslations('name'),
                'slug'                       => $this->getTranslations('slug'),
                'description'                => $this->getTranslations('description'),
                'content'                    => $this->getTranslations('content'),
                'avatar_url'                 => $this->avatar_url,
                'menu_image_url'             => $this->menu_image_url,
                'address'                    => $this->address,
                'online'                     => (bool) $this->online,
                'latitude'                   => $this->latitude,
                'longitude'                  => $this->longitude,
                'phones'                     => $this->phones,
                'seo_title'                  => $this->seo_title,
                'seo_description'            => $this->seo_description,
                'min_price'                  => $this->min_price,
                'max_price'                  => $this->max_price,
                'tickets_count'              => $this->tickets_count,
                'tickets_left'               => $this->tickets_left,
                'tickets_sold'               => $this->tickets_sold,
                'has_urgency_badge'          => $this->has_urgency_badge,
                'has_search_promotion'       => $this->has_search_promotion,
                'has_paypal_stripe_payments' => $this->has_paypal_stripe_payments,
                'has_tickets'                => $this->has_tickets,
                'has_vote_categories'        => $this->has_vote_categories,
                'has_passed'                 => $this->has_passed,
                'dates_sold_out'             => $this->dates_sold_out,
                'is_tour'                    => $this->is_tour,
                'status'                     => $this->status,
                'public_url'                 => $this->public_url,
                'is_passed'                  => $this->is_passed,
                'is_upcoming'                => $this->is_upcoming,
                'start_date'                 => $this->dates?->pluck('start')->min(),
                'start_date_ts'              => $this->dates?->pluck('start')->min()?->timestamp,
                'created_at'                 => $this->created_at?->toISOString(),
                'updated_at'                 => $this->updated_at?->toISOString(),
            ]),
            'relationships' => $this->buildRelationships(),
            'links'         => ['self' => route('api.v1.events.show', ['event' => $this->id])],
        ];
    }

    private function filterFields(array $attributes): array
    {
        if (!$this->only || $this->only === []) {
            return $attributes;
        }

        $filtered = [];
        foreach ($this->only as $field) {
            if (array_key_exists($field, $attributes)) {
                $filtered[$field] = $attributes[$field];
            }
        }

        return $filtered;
    }

    private function buildRelationships(): array
    {
        $rels = [];

        if (in_array('team', $this->includes, true)) {
            $rels['team'] = [
                'data' => $this->team ? ['type' => 'teams', 'id' => (string) $this->team_id] : null,
            ];
        }

        if (in_array('category', $this->includes, true)) {
            $rels['category'] = [
                'data' => $this->category ? ['type' => 'categories', 'id' => (string) $this->category_id] : null,
            ];
        }

        if (in_array('city', $this->includes, true)) {
            $rels['city'] = [
                'data' => $this->city ? ['type' => 'cities', 'id' => (string) $this->city_id] : null,
            ];
        }

        return $rels;
    }
}
