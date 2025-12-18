<?php

namespace Modules\Pipeline\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Pipeline\Models\PipelineType;
use Modules\Pipeline\Config\TypeConfigRegistry;

class PipelineTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $types = [
            [
                'name' => 'deal',
                'display_name' => 'Deals',
                'model_class' => \Modules\Deal\Models\Deal::class,
                'has_probability' => true,
                'has_value' => true,
                'has_due_date' => true,
                'has_priority' => true,
                'order' => 0,
                'icon' => 'heroicon-o-currency-dollar',
                'color' => '#10b981',
            ],
            // [
            //     'name' => 'task',
            //     'display_name' => 'Tasks',
            //     'model_class' => \App\Models\Task::class,
            //     'has_due_date' => true,
            //     'has_priority' => true,
            //     'order' => 1,
            //     'icon' => 'heroicon-o-check-circle',
            //     'color' => '#3b82f6',
            // ],
            // [
            //     'name' => 'lead',
            //     'display_name' => 'Leads',
            //     'model_class' => \App\Models\Lead::class,
            //     'has_probability' => true,
            //     'has_value' => true,
            //     'order' => 2,
            //     'icon' => 'heroicon-o-user-group',
            //     'color' => '#8b5cf6',
            // ],
            // [
            //     'name' => 'ticket',
            //     'display_name' => 'Tickets',
            //     'model_class' => \App\Models\Ticket::class,
            //     'has_priority' => true,
            //     'has_due_date' => true,
            //     'order' => 3,
            //     'icon' => 'heroicon-o-lifebuoy',
            //     'color' => '#f59e0b',
            // ],
            // [
            //     'name' => 'support',
            //     'display_name' => 'Support Cases',
            //     'model_class' => \App\Models\SupportCase::class,
            //     'has_priority' => true,
            //     'has_due_date' => true,
            //     'order' => 4,
            //     'icon' => 'heroicon-o-chat-alt-2',
            //     'color' => '#ec4899',
            // ],
        ];

        foreach ($types as $typeData) {
            PipelineType::updateOrCreate(
                ['name' => $typeData['name']],
                array_merge($typeData, [
                    'is_active' => true,
                    'custom_fields' => [],
                    'validations' => [],
                    'default_stages' => [],
                    'meta' => [],
                ])
            );
        }
    }
}
