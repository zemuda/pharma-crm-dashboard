<?php

namespace Modules\Pipeline\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PipelineTypeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = \Modules\Pipeline\Models\PipelineType::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        $name = $this->faker->unique()->word();

        return [
            'name' => $name,
            'display_name' => ucfirst($name),
            'model_class' => null,
            'table_name' => $name . '_pipeline',
            'foreign_key' => $name . '_id',
            'route_key' => $name . 's',
            'is_active' => true,
            'has_probability' => $this->faker->boolean(),
            'has_value' => $this->faker->boolean(),
            'has_due_date' => $this->faker->boolean(),
            'has_priority' => $this->faker->boolean(),
            'custom_fields' => [],
            'validations' => [],
            'default_stages' => [],
            'order' => $this->faker->numberBetween(0, 100),
            'icon' => 'heroicon-o-collection',
            'color' => $this->faker->hexColor(),
            'meta' => [],
        ];
    }

    /**
     * Indicate that the type is inactive.
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
     * Indicate that the type has specific capabilities.
     *
     * @param  array  $capabilities
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCapabilities(array $capabilities): Factory
    {
        return $this->state(function (array $attributes) use ($capabilities) {
            return array_merge([
                'has_probability' => in_array('probability', $capabilities),
                'has_value' => in_array('value', $capabilities),
                'has_due_date' => in_array('due_date', $capabilities),
                'has_priority' => in_array('priority', $capabilities),
            ], $attributes);
        });
    }

    /**
     * Indicate that the type has custom fields.
     *
     * @param  array  $customFields
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withCustomFields(array $customFields): Factory
    {
        return $this->state(function (array $attributes) use ($customFields) {
            return [
                'custom_fields' => $customFields,
            ];
        });
    }

    /**
     * Indicate that the type has default stages.
     *
     * @param  array  $stages
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function withDefaultStages(array $stages): Factory
    {
        return $this->state(function (array $attributes) use ($stages) {
            return [
                'default_stages' => $stages,
            ];
        });
    }
}
