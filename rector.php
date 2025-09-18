<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use RectorLaravel\Set\LaravelSetProvider;

return RectorConfig::configure()
    ->withPaths([
        __DIR__ . '/packages/goldoni/laravel-virtual-wallet',
        __DIR__ . '/packages/goldoni/laravel-model-permissions',
        __DIR__ . '/packages/goldoni/laravel-teams',
        __DIR__ . '/app',
    ])

    ->withSkip([
        __DIR__ . '/bootstrap/*',
        __DIR__ . '/storage/*',
        __DIR__ . '/vendor/*',
        __DIR__ . '/node_modules/*',
        __DIR__ . '/public/*',
    ])

    ->withParallel()

    ->withImportNames(removeUnusedImports: true)

    ->withSets([
        LevelSetList::UP_TO_PHP_84,
        SetList::TYPE_DECLARATION,
    ])

    ->withPreparedSets(
        deadCode: true,
        codeQuality: true,
        codingStyle: true,
        naming: true,
        privatization: true,
        typeDeclarations: true,
        rectorPreset: true,
    )

    ->withSetProviders(LaravelSetProvider::class)
    ->withComposerBased(laravel: true);
