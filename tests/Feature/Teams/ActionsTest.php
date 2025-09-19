<?php

declare(strict_types=1);

use App\Models\User;
use Goldoni\LaravelTeams\Actions\AddTeamMember;
use Goldoni\LaravelTeams\Actions\ChangeTeamMemberRole;
use Goldoni\LaravelTeams\Actions\CreateTeam;
use Goldoni\LaravelTeams\Actions\DeleteTeam;
use Goldoni\LaravelTeams\Actions\RemoveTeamMember;
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
    $this->actingAs($user);
    $team = app(CreateTeam::class)->handle($user, 'Acme');
    expect($team)->toBeInstanceOf(Team::class)
        ->and($user->fresh()->current_team_id)->toBe($team->id)
        ->and(
            TeamUser::query()
                ->where('team_id', $team->id)
                ->where('user_id', $user->id)
                ->where('role', TeamRoleEnum::OWNER->value)
                ->exists()
        )->toBeTrue();
    Event::assertDispatched(TeamCreated::class);
});

it('add change remove member and dispatch events', function (): void {
    Event::fake();
    $user = createAdmin();
    $this->actingAs($user);
    $team   = app(CreateTeam::class)->handle($user, 'Acme');
    $member = User::factory()->create();
    app(AddTeamMember::class)->handle($team->fresh(), $member);
    expect($team->fresh()->memberships()->where('user_id', $member->id)->value('role'))->toBe(TeamRoleEnum::MEMBER);
    Event::assertDispatched(MemberAdded::class);
    app(ChangeTeamMemberRole::class)->handle($team->fresh(), $member, TeamRoleEnum::ADMIN);
    expect($team->fresh()->memberships()->where('user_id', $member->id)->value('role'))->toBe(TeamRoleEnum::ADMIN);
    Event::assertDispatched(MemberRoleChanged::class);
    app(RemoveTeamMember::class)->handle($team->fresh(), $member);
    expect($team->fresh()->memberships()->where('user_id', $member->id)->exists())->toBeFalse();
    Event::assertDispatched(MemberRemoved::class);
});

it('switchTeam switches when user belongs', function (): void {
    $user = createAdmin();
    $this->actingAs($user);
    $team   = app(CreateTeam::class)->handle($user, 'Acme');
    $member = User::factory()->create();
    $member->teams()->attach($team->id, [
        'role' => TeamRoleEnum::MEMBER->value,
        'ulid' => (string) Str::ulid(),
    ]);
    $this->actingAs($member);
    app(SwitchTeam::class)->handle($member, $team->fresh());
    expect($member->fresh()->current_team_id)->toBe($team->id);
});

it('transferOwnership updates pivot and team owner and dispatches event', function (): void {
    Event::fake();
    $user = createAdmin();
    $this->actingAs($user);
    $team     = app(CreateTeam::class)->handle($user, 'Acme');
    $newOwner = User::factory()->create();
    app(AddTeamMember::class)->handle($team->fresh(), $newOwner, TeamRoleEnum::ADMIN);
    app(TransferOwnership::class)->handle($team->fresh(), $newOwner);

    $team = $team->fresh();
    expect($team->owner_id)->toBe($newOwner->id)
        ->and(
            TeamUser::query()
                ->where('team_id', $team->id)
                ->where('user_id', $user->id)
                ->value('role')
        )->toBe(TeamRoleEnum::ADMIN->value)
        ->and(
            TeamUser::query()
                ->where('team_id', $team->id)
                ->where('user_id', $newOwner->id)
                ->value('role')
        )->toBe(TeamRoleEnum::OWNER->value);
    Event::assertDispatched(TeamOwnershipTransferred::class);
});

it('deleteTeam deletes memberships and team and dispatches event', function (): void {
    Event::fake();
    $user = createAdmin();
    $this->actingAs($user);
    $team = app(CreateTeam::class)->handle($user, 'Acme');
    app(DeleteTeam::class)->handle($team->fresh());
    expect(Team::query()->whereKey($team->id)->exists())->toBeFalse()
        ->and(TeamUser::query()->where('team_id', $team->id)->exists())->toBeFalse();
    Event::assertDispatched(TeamDeleted::class);
});
