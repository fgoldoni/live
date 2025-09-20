<?php

namespace App\Nova\Repeater;

use Core\Rules\Phone;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LinePhoneSms extends Repeatable
{
    #[\Override]
    public static function label(): string
    {
        return __('SMS');
    }
    #[\Override]
    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Message'), 'message')
                ->placeholder('SMS')
                ->default(fn () => 'SMS')
                ->rules('required', 'max:255'),

            Text::make(__('SMS'), 'number')
                ->placeholder('+491738779485')
                ->rules('required', 'max:255', new Phone),
        ];
    }
}
