<?php

declare(strict_types=1);

use App\Models\User;
use Goldoni\LaravelTeams\Models\Team;

it('switches users away from deleted current team when observer installed', function (): void {
    $applicationUser = User::factory()->create();
    $teamRecord      = Team::query()->create([
        'ulid'     => (string) Str::ulid(),
        'name'     => 'Acme',
        'owner_id' => $applicationUser->id,
    ]);

    $applicationUser->forceFill(['current_team_id' => $teamRecord->id])->save();
    $applicationUser->refresh();
    expect($applicationUser->current_team_id)->toBe($teamRecord->id);

    $teamRecord->delete();
    $applicationUser->refresh();
    expect($applicationUser->current_team_id)->toBeNull();
});
