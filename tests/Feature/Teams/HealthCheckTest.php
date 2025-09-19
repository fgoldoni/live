<?php

declare(strict_types=1);

it('artisan teams health passes', function (): void {
    $this->artisan('teams:health')->assertSuccessful();
});
