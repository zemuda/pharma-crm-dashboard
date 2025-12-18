<?php

namespace Modules\Deal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class DealFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Deal\Models\Deal::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

