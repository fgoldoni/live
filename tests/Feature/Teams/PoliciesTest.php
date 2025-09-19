<?php

declare(strict_types=1);

use App\Models\User;
use Goldoni\LaravelTeams\Actions\CreateTeam;
use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Illuminate\Support\Str;

it('owner can manage team by policy and members by manageMembers', function (): void {
    $user  = createAdmin();
    $team  = app(CreateTeam::class)->handle($user, 'Acme');
    $this->actingAs($user);
    expect($user->can('view', $team))->toBeTrue()
        ->and($user->can('update', $team))->toBeTrue()
        ->and($user->can('delete', $team))->toBeTrue()
        ->and($user->can('manageMembers', $team))->toBeTrue();
});

it('member without permissions cannot delete team', function (): void {
    $user   = createAdmin();
    $team   = app(CreateTeam::class)->handle($user, 'Acme');
    $member = User::factory()->create();
    $member->teams()->attach($team->id, ['role' => TeamRoleEnum::MEMBER->value, 'ulid' => (string) Str::ulid()]);
    $this->actingAs($member);
    expect($member->can('delete', $team))->toBeFalse();
});
