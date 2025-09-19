<?php

declare(strict_types=1);

use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Goldoni\LaravelTeams\Models\Team;
use Goldoni\LaravelTeams\Models\TeamUser;
use Illuminate\Support\Facades\Schema;

it('teams tables exist', function (): void {
    expect(Schema::hasTable('teams'))->toBeTrue()
        ->and(Schema::hasTable('team_user'))->toBeTrue();
});

it('team has ulid and route key is ulid', function (): void {
    $team = Team::factory()->create();
    expect($team->ulid)->not()->toBeEmpty()
        ->and($team->getRouteKeyName())->toBe('ulid');
});

it('team relations owner users memberships work', function (): void {
    $team = Team::factory()->create();
    expect($team->owner()->exists())->toBeTrue();
    expect($team->users()->count())->toBeGreaterThanOrEqual(0);
    expect($team->memberships()->count())->toBeGreaterThanOrEqual(0);
});

it('team_user casts role to enum and helpers work', function (): void {
    $teamUser = TeamUser::factory()->create(['role' => TeamRoleEnum::ADMIN->value]);
    expect($teamUser->role)->toBeInstanceOf(TeamRoleEnum::class)
        ->and($teamUser->isAdmin())->toBeTrue()
        ->and($teamUser->isOwner())->toBeFalse()
        ->and($teamUser->isMember())->toBeFalse()
        ->and($teamUser->isViewer())->toBeFalse();
});

it('team_user scope forUser filters correctly', function (): void {
    $a   = TeamUser::factory()->create();
    $b   = TeamUser::factory()->create();
    $ids = TeamUser::query()->forUser($a->user_id)->pluck('id');
    expect($ids)->toContain($a->id)->not()->toContain($b->id);
});
