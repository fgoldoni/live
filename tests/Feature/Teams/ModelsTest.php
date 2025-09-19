<?php

declare(strict_types=1);

use App\Models\User;
use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Goldoni\LaravelTeams\Models\Team;
use Goldoni\LaravelTeams\Models\TeamUser;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

it('teams tables exist', function (): void {
    expect(Schema::hasTable('teams'))->toBeTrue()
        ->and(Schema::hasTable('team_user'))->toBeTrue();
});

it('team has ulid and route key is ulid', function (): void {
    $owner = User::factory()->create();
    $team  = Team::query()->create([
        'name'     => 'Acme',
        'owner_id' => $owner->id,
    ]);
    expect($team->ulid)->not()->toBeEmpty()
        ->and($team->getRouteKeyName())->toBe('ulid');
});

it('team relations owner users memberships work', function (): void {
    $owner = User::factory()->create();
    $team  = Team::query()->create([
        'name'     => 'Acme',
        'owner_id' => $owner->id,
    ]);
    TeamUser::query()->create([
        'team_id' => $team->id,
        'user_id' => $owner->id,
        'role'    => TeamRoleEnum::OWNER->value,
        'ulid'    => (string) Str::ulid(),
    ]);
    $u1 = User::factory()->create();
    $u2 = User::factory()->create();
    TeamUser::query()->create([
        'team_id' => $team->id,
        'user_id' => $u1->id,
        'role'    => TeamRoleEnum::MEMBER->value,
        'ulid'    => (string) Str::ulid(),
    ]);
    TeamUser::query()->create([
        'team_id' => $team->id,
        'user_id' => $u2->id,
        'role'    => TeamRoleEnum::ADMIN->value,
        'ulid'    => (string) Str::ulid(),
    ]);
    $team->refresh();
    expect($team->owner()->exists())->toBeTrue();
    expect($team->users()->count())->toBeGreaterThanOrEqual(1);
    expect($team->memberships()->count())->toBeGreaterThanOrEqual(1);
});

it('team_user casts role to enum and helpers work', function (): void {
    $owner = User::factory()->create();
    $team  = Team::query()->create([
        'name'     => 'Acme',
        'owner_id' => $owner->id,
    ]);
    $member   = User::factory()->create();
    $teamUser = TeamUser::query()->create([
        'team_id' => $team->id,
        'user_id' => $member->id,
        'role'    => TeamRoleEnum::ADMIN->value,
        'ulid'    => (string) Str::ulid(),
    ]);
    expect($teamUser->role)->toBeInstanceOf(TeamRoleEnum::class)
        ->and($teamUser->isAdmin())->toBeTrue()
        ->and($teamUser->isOwner())->toBeFalse()
        ->and($teamUser->isMember())->toBeFalse()
        ->and($teamUser->isViewer())->toBeFalse();
});

it('team_user scope forUser filters correctly', function (): void {
    $owner = User::factory()->create();
    $team  = Team::query()->create([
        'name'     => 'Acme',
        'owner_id' => $owner->id,
    ]);
    $userA    = User::factory()->create();
    $userB    = User::factory()->create();
    $teamUser = TeamUser::query()->create([
        'team_id' => $team->id,
        'user_id' => $userA->id,
        'role'    => TeamRoleEnum::MEMBER->value,
        'ulid'    => (string) Str::ulid(),
    ]);
    $b = TeamUser::query()->create([
        'team_id' => $team->id,
        'user_id' => $userB->id,
        'role'    => TeamRoleEnum::VIEWER->value,
        'ulid'    => (string) Str::ulid(),
    ]);
    $ids = TeamUser::query()->forUser($userA->id)->pluck('id');
    expect($ids)->toContain($teamUser->id)->not()->toContain($b->id);
});
