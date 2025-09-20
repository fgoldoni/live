<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LineTicketOptionMinus extends Repeatable
{
    #[\Override]
    public static function label(): string
    {
        return __('Minus');
    }

    #[\Override]
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Minus', 'name')
                ->rules('required', 'max:255'),
        ];
    }
}
