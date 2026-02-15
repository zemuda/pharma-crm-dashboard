<?php

namespace Modules\ERP\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CountryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\ERP\Models\Country::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

