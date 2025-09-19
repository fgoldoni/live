<?php

declare(strict_types=1);

use Goldoni\LaravelTeams\Actions\RemoveTeamMember;
use App\Models\User;
use Goldoni\LaravelTeams\Actions\AddTeamMember;
use Goldoni\LaravelTeams\Actions\ChangeTeamMemberRole;
use Goldoni\LaravelTeams\Actions\CreateTeam;
use Goldoni\LaravelTeams\Actions\DeleteTeam;
use Goldoni\LaravelTeams\Actions\SwitchTeam;
use Goldoni\LaravelTeams\Actions\TransferOwnership;
use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Goldoni\LaravelTeams\Events\MemberAdded;
use Goldoni\LaravelTeams\Events\MemberRemoved;
use Goldoni\LaravelTeams\Events\MemberRoleChanged;
use Goldoni\LaravelTeams\Events\TeamCreated;
use Goldoni\LaravelTeams\Events\TeamDeleted;
use Goldoni\LaravelTeams\Events\TeamOwnershipTransferred;
use Goldoni\LaravelTeams\Models\Team;
use Goldoni\LaravelTeams\Models\TeamUser;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Str;

it('createTeam creates records and dispatches event', function (): void {
    Event::fake();
    $user = createAdmin();
    $team = app(CreateTeam::class)->handle($user, 'Acme');
    expect($team)->toBeInstanceOf(Team::class)
        ->and($user->current_team_id)->toBe($team->id)
        ->and(TeamUser::query()->where('team_id', $team->id)->where('user_id', $user->id)->where('role', TeamRoleEnum::OWNER->value)->exists())->toBeTrue();
    Event::assertDispatched(TeamCreated::class);
});

it('add change remove member and dispatch events', function (): void {
    Event::fake();
    $user  = createAdmin();
    $team   = app(CreateTeam::class)->handle($user, 'Acme');
    $member = User::factory()->create();
    app(AddTeamMember::class)->handle($team, $member, TeamRoleEnum::MEMBER);
    expect($team->memberships()->where('user_id', $member->id)->value('role'))->toBe(TeamRoleEnum::MEMBER->value);
    Event::assertDispatched(MemberAdded::class);
    app(ChangeTeamMemberRole::class)->handle($team, $member, TeamRoleEnum::ADMIN);
    expect($team->memberships()->where('user_id', $member->id)->value('role'))->toBe(TeamRoleEnum::ADMIN->value);
    Event::assertDispatched(MemberRoleChanged::class);
    app(RemoveTeamMember::class)->handle($team, $member);
    expect($team->memberships()->where('user_id', $member->id)->exists())->toBeFalse();
    Event::assertDispatched(MemberRemoved::class);
});

it('switchTeam switches when user belongs', function (): void {
    $user  = createAdmin();
    $team   = app(CreateTeam::class)->handle($user, 'Acme');
    $member = User::factory()->create();
    $member->teams()->attach($team->id, ['role' => TeamRoleEnum::MEMBER->value, 'ulid' => (string) Str::ulid()]);
    app(SwitchTeam::class)->handle($member, $team);
    expect($member->current_team_id)->toBe($team->id);
});

it('transferOwnership updates pivot and team owner and dispatches event', function (): void {
    Event::fake();
    $user = createAdmin();
    $team     = app(CreateTeam::class)->handle($user, 'Acme');
    $newOwner = User::factory()->create();
    app(AddTeamMember::class)->handle($team, $newOwner, TeamRoleEnum::ADMIN);
    app(TransferOwnership::class)->handle($team, $newOwner);
    expect($team->fresh()->owner_id)->toBe($newOwner->id)
        ->and(TeamUser::query()->where('team_id', $team->id)->where('user_id', $user->id)->value('role'))->toBe(TeamRoleEnum::ADMIN->value)
        ->and(TeamUser::query()->where('team_id', $team->id)->where('user_id', $newOwner->id)->value('role'))->toBe(TeamRoleEnum::OWNER->value);
    Event::assertDispatched(TeamOwnershipTransferred::class);
});

it('deleteTeam deletes memberships and team and dispatches event', function (): void {
    Event::fake();
    $user = createAdmin();
    $team  = app(CreateTeam::class)->handle($user, 'Acme');
    app(DeleteTeam::class)->handle($team);
    expect(Team::query()->whereKey($team->id)->exists())->toBeFalse()
        ->and(TeamUser::query()->where('team_id', $team->id)->exists())->toBeFalse();
    Event::assertDispatched(TeamDeleted::class);
});
