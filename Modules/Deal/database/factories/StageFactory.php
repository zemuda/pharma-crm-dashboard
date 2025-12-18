<?php

namespace Modules\Deal\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     */
    protected $model = \Modules\Deal\Models\Stage::class;

    /**
     * Define the model's default state.
     */
    public function definition(): array
    {
        return [];
    }
}

