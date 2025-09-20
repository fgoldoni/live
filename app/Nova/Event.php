<?php

declare(strict_types=1);

namespace App\Nova;

use App\Nova\Filters\OnlineFilter;
use App\Nova\Filters\TeamFilter;
use App\Nova\Repeater\LinePhoneCall;
use App\Nova\Repeater\LinePhoneSms;
use App\Nova\Repeater\LinePhoneWhatsapp;
use Ebess\AdvancedNovaMediaLibrary\Fields\Images;
use Illuminate\Database\Eloquent\Builder;
use Laravel\Nova\Fields\Avatar;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\Repeater;
use Laravel\Nova\Fields\Slug;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Fields\Textarea;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Panel;
use Laravel\Nova\Tabs\Tab;
use Modules\Categories\Models\Category as CategoryModel;
use Modules\Countries\Models\City as CityModel;
use Modules\Countries\Models\Country as CountryModel;
use Modules\Countries\Models\Division as DivisionModel;
use Spatie\NovaTranslatable\Translatable;
use Spatie\TagsField\Tags;

class Event extends Resource
{
    public static string $model = \Modules\Events\Models\Event::class;

    public static $title = 'name';

    public static $trafficCop = false;

    public static $search = ['id', 'name', 'address'];

    public static function relatableQuery(NovaRequest $request, $query): Builder
    {
        if (auth()->user()?->isSuperAdmin()) {
            return $query;
        }

        return $query->where('team_id', $request->user()->currentTeam()?->value('id'));
    }

    public function fields(NovaRequest $request): array
    {
        return [
            ID::make()->sortable(),

            Panel::make(__('Base'), [
                Avatar::make(__('Avatar'), 'avatar')->disk(config('filesystems.default'))->path('avatars'),
                Text::make(__('Name'), 'name')->rules('required', 'max:255')->sortable(),
                Boolean::make(__('Online'), 'online')->sortable()->rules('required', 'boolean')->default(true),
                Text::make(__('Address'), 'address')->nullable()->hideFromIndex(),
                Text::make(__('Dress Code'), 'dress_code')->rules('nullable', 'max:32')->hideFromIndex(),
                Slug::make(__('Slug'), 'slug')
                    ->rules('required', 'max:160', 'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/')
                    ->creationRules('unique:events,slug')
                    ->updateRules('unique:events,slug,{{resourceId}}')
                    ->hideFromIndex()
                    ->from('Name'),
            ])->collapsible()->collapsedByDefault(),

            Panel::make(__('Media'), [
                Images::make(__('Promo'), 'promo')
                    ->conversionOnPreview('thumb')
                    ->conversionOnDetailView('thumb')
                    ->conversionOnIndexView('thumb')
                    ->conversionOnForm('thumb')
                    ->onlyOnForms(),
                Images::make(__('Add Additional Photos') . ' (Max: 10)', 'images')
                    ->conversionOnPreview('thumb')
                    ->conversionOnDetailView('thumb')
                    ->conversionOnIndexView('thumb')
                    ->conversionOnForm('thumb')
                    ->onlyOnForms(),
            ])->collapsible()->collapsedByDefault(),

            Tab::group(__('Content'), [
                Tab::make(__('Description'), [
                    Translatable::make([
                        Textarea::make(__('Description'), 'description')->alwaysShow(),
                    ])->locales(['fr', 'en', 'de']),
                ]),
                Tab::make(__('Content'), [
                    Translatable::make([
                        Markdown::make(__('Content'), 'content')->nullable(),
                    ])->locales(['fr', 'en', 'de']),
                ]),
            ]),

            Tab::group(__('Location'), [
                Tab::make(__('Coordinates'), [
                    Number::make(__('Latitude'), 'latitude')->step(0.0000001)->min(-90)->max(90)->nullable()->hideFromIndex(),
                    Number::make(__('Longitude'), 'longitude')->step(0.0000001)->min(-180)->max(180)->nullable()->hideFromIndex(),
                ]),
                Tab::make(__('Relations'), [
                    BelongsTo::make(__('Country'), 'country', Country::class)
                        ->nullable()
                        ->searchable()
                        ->creationRules('exists:' . (new CountryModel())->getTable() . ',id')
                        ->updateRules('exists:' . (new CountryModel())->getTable() . ',id'),
                    BelongsTo::make(__('Division'), 'division', Division::class)
                        ->nullable()
                        ->searchable()
                        ->creationRules('exists:' . (new DivisionModel())->getTable() . ',id')
                        ->updateRules('exists:' . (new DivisionModel())->getTable() . ',id'),
                    BelongsTo::make(__('City'), 'city', City::class)
                        ->nullable()
                        ->searchable()
                        ->creationRules('exists:' . (new CityModel())->getTable() . ',id')
                        ->updateRules('exists:' . (new CityModel())->getTable() . ',id'),
                ]),
            ]),

            Panel::make(__('Ownership & Team'), [
                BelongsTo::make(__('User'), 'user', User::class)
                    ->nullable()
                    ->showOnIndex()
                    ->canSee(fn (NovaRequest $r) => $r->user()?->isSuperAdmin())
                    ->readonly(fn (NovaRequest $r) => !$r->isCreateOrAttachRequest())
                    ->creationRules('exists:users,id'),

                BelongsTo::make(__('Manager'), 'manager', User::class)
                    ->placeholder(__('Select a manager'))
                    ->nullable()
                    ->readonly(fn (NovaRequest $r) => !$r->user()->isSuperAdmin())
                    ->creationRules('exists:users,id')
                    ->updateRules('exists:users,id')
                    ->relatableQueryUsing(fn (NovaRequest $r, Builder $q) => $q->role('manager'))
                    ->canSee(fn (NovaRequest $r) => $r->user()->hasPermissionTo('manager')),

                BelongsTo::make(__('Team'), 'team', Team::class)
                    ->nullable()
                    ->showOnIndex()
                    ->canSee(fn (NovaRequest $r) => $r->user()?->isSuperAdmin())
                    ->readonly(fn (NovaRequest $r) => !$r->isCreateOrAttachRequest())
                    ->creationRules('exists:teams,id'),

                BelongsTo::make(__('Category'), 'category', Category::class)
                    ->nullable()
                    ->searchable()
                    ->creationRules('exists:' . (new CategoryModel())->getTable() . ',id')
                    ->updateRules('exists:' . (new CategoryModel())->getTable() . ',id'),
            ])->collapsible()->collapsedByDefault(),

            Panel::make(__('Phones'), [
                Repeater::make(__('Call, WhatsApp, and SMS'), 'phones')
                    ->repeatables([
                        LinePhoneCall::make()->confirmRemoval(),
                        LinePhoneWhatsapp::make()->confirmRemoval(),
                        LinePhoneSms::make()->confirmRemoval(),
                    ])
                    ->asJson(),
                Number::make(__('WhatsApp Limit Per Client'), 'whatsapp_limit_per_client')
                    ->min(0)->step(1)->default(100)->rules('integer', 'min:0'),
                Number::make(__('SMS Limit Per Client'), 'sms_limit_per_client')
                    ->min(0)->step(1)->default(100)->rules('integer', 'min:0'),
            ])->collapsible()->collapsedByDefault(),

            Panel::make(__('Tags'), [
                Tags::make(__('Your hashtags') . ' ' . __('Max: 6'), 'tags')
                    ->hideFromIndex()
                    ->withLinkToTagResource()
                    ->limit(6)
                    ->withMeta(['placeholder' => __('e.g. Afrobeats, Festival...')])
                    ->type(\Modules\Events\Models\Event::class),
            ])->collapsible()->collapsedByDefault(),

            Panel::make(__('SEO'), [
                Text::make(__('SEO Title'), 'seo_title')->nullable()->hideFromIndex(),
                Textarea::make(__('SEO Description'), 'seo_description')->nullable()->hideFromIndex(),
            ])->collapsible()->collapsedByDefault(),

            Panel::make(__('Meta'), [
                Text::make(__('Created At'), fn () => optional($this->created_at)?->format('Y-m-d H:i'))->onlyOnIndex()->sortable(),
                Text::make(__('Updated At'), fn () => optional($this->updated_at)?->format('Y-m-d H:i'))->onlyOnIndex()->sortable(),
            ])->collapsible()->collapsedByDefault(),
        ];
    }

    public function filters(NovaRequest $request): array
    {
        return [
            new OnlineFilter,
            new TeamFilter,
        ];
    }

    public function cards(NovaRequest $request): array
    {
        return [];
    }

    public function lenses(NovaRequest $request): array
    {
        return [];
    }

    public function actions(NovaRequest $request): array
    {
        return [];
    }
}
