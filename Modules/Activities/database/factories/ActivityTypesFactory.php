<?php

namespace Modules\Activities\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ActivityTypesFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Activities\Models\ActivityTypes::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

