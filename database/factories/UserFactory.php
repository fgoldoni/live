<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\User;
use Goldoni\LaravelTeams\Actions\AddTeamMember;
use Goldoni\LaravelTeams\Actions\CreateTeam;
use Goldoni\LaravelTeams\Enums\TeamRoleEnum;
use Goldoni\LaravelTeams\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected static ?string $password = null;

    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'ulid'              => Str::ulid(),
            'email'             => fake()->unique()->safeEmail(),
            'phone'             => fake()->e164PhoneNumber(),
            'email_verified_at' => now(),
            'password'          => static::$password ??= Hash::make('password'),
            'remember_token'    => Str::random(10),
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified_at' => null]);
    }

    public function withRoleKey(string $key): static
    {
        $label = $this->roleLabel($key);

        return $this->afterCreating(function (User $user) use ($label): void {
            $role = Role::findByName($label, $this->guardName());
            $user->assignRole($role);
        });
    }

    public function withRoleLabel(string $label): static
    {
        return $this->afterCreating(function (User $user) use ($label): void {
            $role = Role::findByName($label, $this->guardName());
            $user->assignRole($role);
        });
    }

    public function asSuperAdmin(): static
    {
        return $this->withRoleLabel($this->superAdminLabel());
    }

    public function asManager(): static
    {
        return $this->withRoleLabel($this->roleLabel('manager'));
    }

    public function asSeller(): static
    {
        return $this->withRoleLabel($this->roleLabel('seller'));
    }

    public function asUser(): static
    {
        return $this->withRoleLabel($this->roleLabel('user'));
    }

    private function guardName(): string
    {
        return (string) config('model-permissions.guard_name', config('auth.defaults.guard', 'web'));
    }

    private function superAdminLabel(): string
    {
        return (string) config('model-permissions.super_admin_role', 'Super Admin');
    }

    private function roleLabel(string $key): string
    {
        $roles = (array) config('model-permissions.roles', []);

        return (string) ($roles[$key] ?? $key);
    }

    public function withOwnedTeam(?string $name = null): static
    {
        return $this->afterCreating(function (User $user) use ($name): void {
            $teamName = $name ?: "{$user->name}'s Team";
            app(CreateTeam::class)->handle($user, $teamName);
        });
    }

    public function withMembership(Team $team, string $role = 'MEMBER'): static
    {
        return $this->afterCreating(function (User $user) use ($team, $role): void {
            $roleEnum = TeamRoleEnum::from($role);
            if ($roleEnum === TeamRoleEnum::OWNER) {
                $user->forceFill(['current_team_id' => $team->getKey()])->save();
            } else {
                app(AddTeamMember::class)->handle($team, $user, $roleEnum);
            }
        });
    }

    public function withTeam(Team|string|null $team = null, ?string $role = null): static
    {
        return $this->afterCreating(function (User $user) use ($team, $role): void {
            if ($team instanceof Team) {
                $roleEnum = $role ? TeamRoleEnum::from($role) : TeamRoleEnum::MEMBER;
                if ($roleEnum === TeamRoleEnum::OWNER) {
                    $user->forceFill(['current_team_id' => $team->getKey()])->save();
                } else {
                    app(AddTeamMember::class)->handle($team, $user, $roleEnum);
                }
                return;
            }

            $teamName = is_string($team) ? $team : "{$user->name}'s Team";
            app(CreateTeam::class)->handle($user, $teamName);
        });
    }
}
