<?php

declare(strict_types=1);

use App\Models\User;
use Goldoni\LaravelTeams\Actions\CreateTeam;
use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Illuminate\Support\Str;

it('ownedTeams teams allTeams ownsTeam', function (): void {
    $user   = User::factory()->create();
    $team   = app(CreateTeam::class)->handle($user, $user->name . ' Team 1');
    $t2     = app(CreateTeam::class)->handle($user, $user->name . ' Team 2');
    expect($user->ownedTeams()->count())->toBe(2)
        ->and($user->teams()->count())->toBe(2)
        ->and($user->allTeams()->count())->toBe(2)
        ->and($user->ownsTeam($team))->toBeTrue()
        ->and($user->ownsTeam($t2))->toBeTrue();
});

it('belongsToTeam isOnTeam isCurrentTeam switchTeam', function (): void {
    $owner  = User::factory()->create();
    $member = User::factory()->create();
    $team   = app(CreateTeam::class)->handle($owner, 'Alpha');
    $member->teams()->attach($team->id, ['role' => TeamRoleEnum::MEMBER->value, 'ulid' => (string) Str::ulid()]);
    expect($member->isOnTeam($team))->toBeTrue();
    $ok = $member->switchTeam($team);
    expect($ok)->toBeTrue()->and($member->isCurrentTeam($team))->toBeTrue();
});

it('currentTeam fallback selects oldest owned else oldest membership', function (): void {
    $u1     = User::factory()->create();
    $team   = app(CreateTeam::class)->handle($u1, 'First');
    app(CreateTeam::class)->handle($u1, 'Second');
    $u1->forceFill(['current_team_id' => null])->save();
    expect($u1->currentTeam?->id)->toBe($team->id);

    $u2     = User::factory()->create();
    $owner2 = User::factory()->create();
    $owned  = app(CreateTeam::class)->handle($owner2, 'Owned');
    $other  = app(CreateTeam::class)->handle(User::factory()->create(), 'Other');
    $u2->teams()->attach($other->id, ['role' => TeamRoleEnum::MEMBER->value, 'ulid' => (string) Str::ulid()]);
    $u2->teams()->attach($owned->id, ['role' => TeamRoleEnum::ADMIN->value, 'ulid' => (string) Str::ulid()]);
    $u2->forceFill(['current_team_id' => null])->save();
    expect(in_array($u2->currentTeam?->id, [$owned->id, $other->id], true))->toBeTrue();
});

it('team role helpers', function (): void {
    $owner  = User::factory()->create();
    $team   = app(CreateTeam::class)->handle($owner, 'X');

    $admin  = User::factory()->create();
    $member = User::factory()->create();
    $viewer = User::factory()->create();

    $admin->teams()->attach($team->id, ['role' => TeamRoleEnum::ADMIN->value,  'ulid' => (string) Str::ulid()]);
    $member->teams()->attach($team->id, ['role' => TeamRoleEnum::MEMBER->value, 'ulid' => (string) Str::ulid()]);
    $viewer->teams()->attach($team->id, ['role' => TeamRoleEnum::VIEWER->value, 'ulid' => (string) Str::ulid()]);

    expect($owner->teamRole($team))->toBe(TeamRoleEnum::OWNER);

    expect($owner->hasTeamRoleOwner($team))->toBeTrue()
        ->and($admin->hasTeamRoleAdmin($team))->toBeTrue()
        ->and($member->hasTeamRoleMember($team))->toBeTrue()
        ->and($viewer->hasTeamRoleViewer($team))->toBeTrue()
        ->and($owner->hasAnyTeamRole($team, [TeamRoleEnum::ADMIN, TeamRoleEnum::MEMBER]))->toBeFalse()
        ->and($admin->hasAnyTeamRole($team, [TeamRoleEnum::ADMIN, TeamRoleEnum::MEMBER]))->toBeTrue();

    expect($admin->hasTeamRole($team, TeamRoleEnum::ADMIN->value))->toBeTrue();
});
