<?php

declare(strict_types=1);

use Goldoni\LaravelTeams\Enums\TeamRoleEnum;

it('enum has expected values', function (): void {
    expect(TeamRoleEnum::OWNER->value)->toBe('OWNER')
        ->and(TeamRoleEnum::ADMIN->value)->toBe('ADMIN')
        ->and(TeamRoleEnum::MEMBER->value)->toBe('MEMBER')
        ->and(TeamRoleEnum::VIEWER->value)->toBe('VIEWER');
});
