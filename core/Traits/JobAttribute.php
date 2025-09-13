<?php

namespace Core\Traits;

use Illuminate\Support\Carbon;

trait JobAttribute
{
    public function getClosingToFormattedAttribute()
    {
        return $this->closing_to?->format('d, M Y');
    }

    public function getHighlightAttribute(): ?bool
    {
        return $this->highlight_to?->gt(now());
    }

    public function getUrgentAttribute(): ?bool
    {
        return $this->urgent_to?->gt(now());
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at?->diffForHumans();
    }

    public function getLiveAtFormattedAttribute()
    {
        return $this->live_at?->diffForHumans();
    }

    public function getClosingToForEditingAttribute()
    {
        return $this->closing_to?->format('d/m/Y');
    }

    public function setClosingToForEditingAttribute($value)
    {
        $this->closing_to = Carbon::createFromFormat('d/m/Y', $value);
    }
}
