<?php

namespace App\Nova\Repeater;

use Core\Rules\Phone;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LinePhoneWhatsapp extends Repeatable
{
    #[\Override]
    public static function label(): string
    {
        return __('Whatsapp');
    }
    #[\Override]
    public function fields(NovaRequest $request)
    {
        return [
            Text::make(__('Message'), 'message')
                ->placeholder('Whatsapp')
                ->default(fn () => 'Whatsapp')
                ->rules('required', 'max:255'),

            Text::make(__('Whatsapp'), 'number')
                ->placeholder('+491738779485')
                ->rules('required', 'max:255', new Phone),
        ];
    }
}
