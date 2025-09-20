<?php

declare(strict_types=1);

namespace App\Nova;

use App\Models\User as UserModel;
use Laravel\Nova\Fields\Field;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\MorphToMany;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Tabs\Tab;
use Sereny\NovaPermissions\Nova\Permission;
use Sereny\NovaPermissions\Nova\Role;

class User extends Resource
{
    public static string $model = UserModel::class;

    /** @var array<int, string> */
    public static $search = [
        'id', 'name', 'email', 'ulid',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @return array<int, Field>
     */
    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),
            Text::make('Name')->rules('required', 'string', 'max:255'),
            Text::make('Email')
                ->rules('required', 'email', 'max:255')
                ->creationRules('unique:users,email')
                ->updateRules('unique:users,email,{{resourceId}}'),
            Text::make('Roles', fn ($model) => $model->roles->implode('name', ', '))->hide()->showOnIndex()->canSee(
                fn ($request) => $request->user()->hasPermissionTo('manager')
            ),
            \Laravel\Nova\Fields\Tag::make('Roles', 'roles', Role::class)->canSee(
                fn ($request) => $request->user()->isSuperAdmin()
            )->onlyOnForms(),
            Tab::group('Roles & Permissions', [
                MorphToMany::make('Roles', 'roles', Role::class)->canSee(
                    fn ($request) => $request->user()->isSuperAdmin()
                ),

                MorphToMany::make('Permissions', 'permissions', Permission::class)->canSee(
                    fn ($request) => $request->user()->isSuperAdmin()
                ),
            ]),
        ];
    }
}
