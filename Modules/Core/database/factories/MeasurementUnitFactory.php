<?php

namespace Modules\Core\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class MeasurementUnitFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Core\Models\MeasurementUnit::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

