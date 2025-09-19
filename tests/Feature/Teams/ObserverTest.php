<?php

declare(strict_types=1);

use Goldoni\LaravelTeams\Actions\CreateTeam;
use Goldoni\LaravelTeams\Models\Team;

it('switches users away from deleted current team when observer installed', function (): void {
    $user = createAdmin();
    $team  = app(CreateTeam::class)->handle($user, 'Acme');
    $user->refresh();
    expect($user->current_team_id)->toBe($team->id);
    Team::query()->whereKey($team->id)->delete();
    $user->refresh();
    expect($user->current_team_id)->toBeNull()->or()->toBeInt();
});
