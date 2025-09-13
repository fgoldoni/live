<?php

namespace Core\Traits;

use Modules\Teams\Models\Membership;
use Modules\Teams\Models\Team;

trait HasTeamTrait
{
    public function isCurrentTeam($team)
    {
        return $team->id === $this->currentTeam->id;
    }


    public function currentTeam()
    {
        if (is_null($this->current_team_id) && $this->id) {
            $this->switchTeam($this->personalTeam());
        }

        return $this->belongsTo(Team::class, 'current_team_id');
    }


    public function switchTeam($team): bool
    {
        if (! $this->belongsToTeam($team)) {
            return false;
        }

        $this->forceFill([
            'current_team_id' => $team->id,
        ])->save();

        $this->setRelation('currentTeam', $team);

        return true;
    }


    public function allTeams()
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
    }


    public function ownedTeams()
    {
        return $this->hasMany(Team::class);
    }



    public function personalTeam()
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }


    public function ownsTeam($team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->id == $team->{$this->getForeignKey()};
    }

    public function teams()
    {
        return $this->belongsToMany(Team::class, Membership::class)
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }


    public function belongsToTeam($team): bool
    {
        if (is_null($team)) {
            return false;
        }

        return $this->ownsTeam($team) || $this->teams?->contains(fn ($t) => $t->id === $team->id);
    }
}
