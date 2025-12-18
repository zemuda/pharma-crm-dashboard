<?php

namespace Modules\Pipeline\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PipelineFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Pipeline\Models\Pipeline::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->sentence(),
            'is_default' => false,
            'is_active' => true,
            'order' => $this->faker->numberBetween(0, 100),
            'meta' => [
                'color' => $this->faker->hexColor(),
                'created_by' => null,
            ],
        ];
    }

    /**
     * Indicate that the pipeline is default.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function default(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_default' => true,
            ];
        });
    }

    /**
     * Indicate that the pipeline is inactive.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function inactive(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_active' => false,
            ];
        });
    }

    /**
     * Indicate that the pipeline has specific meta data.
     *
     * @param  array  $meta
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withMeta(array $meta): Factory
    {
        return $this->state(function (array $attributes) use ($meta) {
            return [
                'meta' => array_merge($attributes['meta'] ?? [], $meta),
            ];
        });
    }
}
