<?php

declare(strict_types=1);

use App\Models\User;
use App\Models\OneTimePassword;
use App\Models\PasswordlessToken;
use App\Models\WhatsAppMessage;
use Goldoni\LaravelTeams\Models\Team;
use Modules\Events\Models\Event;

return [
    'guard_name'       => 'web',
    'super_admin_role' => 'Super Admin',

    'roles' => [
        'super_admin' => 'Super Admin',
        'manager'     => 'Manager',
        'seller'      => 'Seller',
        'user'        => 'User',
    ],

    'models' => [
        User::class,
        WhatsAppMessage::class,
        OneTimePassword::class,
        PasswordlessToken::class,
        Team::class,
        Event::class,
    ],

    'abilities' => [
        'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny',
        'restore', 'restoreAny', 'forceDelete', 'forceDeleteAny',
        'replicate', 'reorder', 'attach', 'attachAny', 'detach', 'detachAny',
        'transferOwnership', 'leave', 'invite', 'acceptInvite', 'declineInvite',
    ],

    'global_permissions' => [
        'impersonate',
        'nova',
    ],

    'role_ability_map' => [
        'Super Admin' => ['*'],
        'Manager'     => ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore', 'replicate', 'reorder', 'attach', 'attachAny', 'detach', 'detachAny'],
        'Seller'      => ['viewAny', 'view', 'create', 'update', 'delete', 'deleteAny'],
        'User'        => ['viewAny', 'view', 'create', 'update'],
    ],

    'role_model_ability_map' => [
        'Manager' => [
            Team::class => [
                'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny',
                'attach', 'attachAny', 'detach', 'detachAny',
                'invite', 'transferOwnership',
            ],
            User::class => [
                'viewAny', 'view', 'create', 'update', 'delete', 'deleteAny', 'restore', 'restoreAny',
            ],
        ],
        'Seller' => [
            Team::class => [
                'viewAny', 'view',
                'leave', 'acceptInvite', 'declineInvite',
            ],
            User::class => [
                'viewAny', 'view',
            ],
        ],
        'User' => [
            Team::class => [
                'viewAny', 'view', 'create',
                'leave', 'acceptInvite', 'declineInvite',
            ],
            User::class => [
                'view', 'update',
            ],
        ],
    ],

    'role_global_permissions' => [
        'Super Admin' => ['*'],
        'Manager'     => ['impersonate', 'nova'],
        'Seller'      => [],
        'User'        => [],
    ],
];
