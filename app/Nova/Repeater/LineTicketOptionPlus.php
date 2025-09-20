<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LineTicketOptionPlus extends Repeatable
{
    #[\Override]
    public static function label(): string
    {
        return __('Plus');
    }


    #[\Override]
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Plus', 'name')
                ->rules('required', 'max:255'),
        ];
    }
}
