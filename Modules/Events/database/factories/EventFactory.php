<?php

namespace Modules\Events\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class EventFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Events\Models\Event::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

