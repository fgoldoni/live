<?php

namespace App\Nova\Repeater;

use Core\Rules\Phone;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LinePhoneCall extends Repeatable
{
    #[\Override]
    public static function label(): string
    {
        return __('Call');
    }
    #[\Override]
    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Message'), 'message')
                ->placeholder('Phone')
                ->default(fn () => 'Phone')
                ->rules('required', 'max:255'),

            Text::make(__('Call'), 'number')
                ->placeholder('+491738779485')
                ->rules('required', 'max:255', new Phone),
        ];
    }
}
