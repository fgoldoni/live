<?php

namespace App\Nova\Repeater;

use Core\Rules\Phone;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class MobileRepeater extends Repeatable
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
            Select::make('Operator', 'operator')
                ->options(config('app.system.mobiles'))
                ->rules('required'),

            Text::make('Name', 'name')
                ->rules('required', 'max:255'),

            Text::make(__('Call'), 'number')
                ->placeholder('+491738779485')
                ->rules('required', 'max:255', new Phone),
        ];
    }
}
