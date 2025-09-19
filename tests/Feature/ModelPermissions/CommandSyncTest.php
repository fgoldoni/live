<?php

declare(strict_types=1);

it('command model-permissions:sync works', function (): void {
    $this->artisan('model-permissions:sync', ['--with-roles' => true, '--reset' => true])->assertSuccessful();
});
