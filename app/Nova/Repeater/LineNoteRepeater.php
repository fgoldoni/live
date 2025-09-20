<?php

namespace App\Nova\Repeater;

use Laravel\Nova\Fields\Markdown;
use Laravel\Nova\Fields\Repeater\Repeatable;
use Laravel\Nova\Fields\Text;
use Laravel\Nova\Http\Requests\NovaRequest;

class LineNoteRepeater extends Repeatable
{
    #[\Override]
    public static function label(): string
    {
        return __('Note');
    }

    #[\Override]
    public function fields(NovaRequest $request): array
    {
        return [
            Text::make('Title', 'name')
                ->rules('required', 'max:255'),

            Markdown::make('Message', 'name')
                ->rules('required'),
        ];
    }
}
