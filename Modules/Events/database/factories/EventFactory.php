<?php

declare(strict_types=1);

namespace Modules\Events\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Events\Models\Event;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = Event::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}
