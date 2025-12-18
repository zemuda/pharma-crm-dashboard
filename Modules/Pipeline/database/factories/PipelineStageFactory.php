<?php

namespace Modules\Pipeline\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Pipeline\Models\Pipeline;

class PipelineStageFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Pipeline\Models\PipelineStage::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'pipeline_id' => Pipeline::factory(),
            'name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'color' => $this->faker->hexColor(),
            'order' => $this->faker->numberBetween(0, 10),
            'probability' => $this->faker->optional()->randomFloat(2, 0, 100),
            'is_default' => false,
            'meta' => null,
        ];
    }

    /**
     * Indicate that the stage is default.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function default(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'is_default' => true,
                'order' => 0,
            ];
        });
    }

    /**
     * Indicate that the stage has probability.
     *
     * @param  float  $probability
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withProbability(float $probability): Factory
    {
        return $this->state(function (array $attributes) use ($probability) {
            return [
                'probability' => $probability,
            ];
        });
    }

    /**
     * Indicate that the stage has specific meta data.
     *
     * @param  array  $meta
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withMeta(array $meta): Factory
    {
        return $this->state(function (array $attributes) use ($meta) {
            return [
                'meta' => $meta,
            ];
        });
    }

    /**
     * Associate with a specific pipeline.
     *
     * @param  \Modules\Pipeline\Entities\Pipeline|int  $pipeline
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function forPipeline($pipeline): Factory
    {
        return $this->state(function (array $attributes) use ($pipeline) {
            return [
                'pipeline_id' => $pipeline instanceof Pipeline ? $pipeline->id : $pipeline,
            ];
        });
    }
}
