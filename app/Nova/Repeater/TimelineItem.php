<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Boolean;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class TimelineItem extends Repeatable
{
    /**
     * Get the fields displayed by the repeatable.
     *
     * @return array<int, \Laravel\Nova\Fields\Field>
     */
    #[\Override]
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Label', 'label')
                ->rules('required', 'max:255'),

            Select::make('Start', 'start')
                ->options(config('app.system.times'))
                ->rules('required'),

            Select::make('End', 'end')
                ->options(config('app.system.times'))
                ->rules('nullable'),

            Boolean::make('Free', 'free')
                ->default(false)
                ->rules('required'),
        ];
    }
}
