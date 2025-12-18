<?php

namespace Modules\Pipeline\Config;

use Modules\Pipeline\Services\TypeConfigService;

class TypeConfigRegistry
{
    /**
     * Register default pipeline types.
     *
     * @param TypeConfigService $service
     * @return void
     */
    public static function registerDefaults(TypeConfigService $service): void
    {
        // Deal type configuration
        $service->registerType([
            'name' => 'deal',
            'display_name' => 'Deals',
            'model_class' => \Modules\Deal\Models\Deal::class,
            'has_probability' => true,
            'has_value' => true,
            'has_due_date' => true,
            'has_priority' => true,
            'custom_fields' => [
                'value' => [
                    'type' => 'number',
                    'label' => 'Deal Value',
                    'required' => false,
                    'validation' => 'nullable|numeric|min:0',
                ],
                'expected_close_date' => [
                    'type' => 'date',
                    'label' => 'Expected Close Date',
                    'required' => false,
                    'validation' => 'nullable|date|after:today',
                ],
                'source' => [
                    'type' => 'select',
                    'label' => 'Source',
                    'options' => ['website', 'referral', 'outbound', 'partner', 'event'],
                    'required' => false,
                    'validation' => 'nullable|string|in:website,referral,outbound,partner,event',
                ],
            ],
            'validations' => [
                'value' => 'nullable|numeric|min:0',
                'expected_close_date' => 'nullable|date|after:today',
                'source' => 'nullable|string|in:website,referral,outbound,partner,event',
            ],
            'default_stages' => [
                [
                    'name' => 'Lead',
                    'color' => '#f59e0b',
                    'order' => 0,
                    'probability' => 10,
                    'meta' => ['is_initial' => true]
                ],
                [
                    'name' => 'Qualified',
                    'color' => '#10b981',
                    'order' => 1,
                    'probability' => 30,
                    'meta' => ['is_qualification' => true]
                ],
                [
                    'name' => 'Proposal',
                    'color' => '#3b82f6',
                    'order' => 2,
                    'probability' => 50,
                    'meta' => ['has_proposal' => true]
                ],
                [
                    'name' => 'Negotiation',
                    'color' => '#8b5cf6',
                    'order' => 3,
                    'probability' => 70,
                    'meta' => ['is_negotiation' => true]
                ],
                [
                    'name' => 'Closed Won',
                    'color' => '#047857',
                    'order' => 4,
                    'probability' => 100,
                    'meta' => ['is_final' => true, 'is_success' => true]
                ],
                [
                    'name' => 'Closed Lost',
                    'color' => '#ef4444',
                    'order' => 5,
                    'probability' => 0,
                    'meta' => ['is_final' => true, 'is_success' => false]
                ],
            ],
            'icon' => 'heroicon-o-currency-dollar',
            'color' => '#10b981',
            'order' => 0,
            'meta' => [
                'reports' => ['forecast', 'conversion_rate', 'average_deal_size'],
                'permissions' => ['view_all', 'edit_all', 'delete_all'],
            ],
        ]);

        // Task type configuration
        // $service->registerType([
        //     'name' => 'task',
        //     'display_name' => 'Tasks',
        //     // 'model_class' =>  \Modules\Task\Models\Task::class,
        //     'has_due_date' => true,
        //     'has_priority' => true,
        //     'custom_fields' => [
        //         'estimated_hours' => [
        //             'type' => 'number',
        //             'label' => 'Estimated Hours',
        //             'required' => false,
        //             'validation' => 'nullable|numeric|min:0',
        //         ],
        //         'actual_hours' => [
        //             'type' => 'number',
        //             'label' => 'Actual Hours',
        //             'required' => false,
        //             'validation' => 'nullable|numeric|min:0',
        //         ],
        //         'assignee_id' => [
        //             'type' => 'user',
        //             'label' => 'Assignee',
        //             'required' => false,
        //             'validation' => 'nullable|exists:users,id',
        //         ],
        //         'project_id' => [
        //             'type' => 'select',
        //             'label' => 'Project',
        //             'required' => false,
        //             'validation' => 'nullable|exists:projects,id',
        //         ],
        //     ],
        //     'validations' => [
        //         'due_date' => 'nullable|date',
        //         'estimated_hours' => 'nullable|numeric|min:0',
        //         'actual_hours' => 'nullable|numeric|min:0',
        //         'assignee_id' => 'nullable|exists:users,id',
        //         'project_id' => 'nullable|exists:projects,id',
        //     ],
        //     'default_stages' => [
        //         [
        //             'name' => 'To Do',
        //             'color' => '#f59e0b',
        //             'order' => 0,
        //             'meta' => ['is_backlog' => true]
        //         ],
        //         [
        //             'name' => 'In Progress',
        //             'color' => '#3b82f6',
        //             'order' => 1,
        //             'meta' => ['is_active' => true]
        //         ],
        //         [
        //             'name' => 'Review',
        //             'color' => '#8b5cf6',
        //             'order' => 2,
        //             'meta' => ['needs_review' => true]
        //         ],
        //         [
        //             'name' => 'Completed',
        //             'color' => '#10b981',
        //             'order' => 3,
        //             'meta' => ['is_complete' => true]
        //         ],
        //         [
        //             'name' => 'Blocked',
        //             'color' => '#ef4444',
        //             'order' => 4,
        //             'meta' => ['is_blocked' => true]
        //         ],
        //     ],
        //     'icon' => 'heroicon-o-check-circle',
        //     'color' => '#3b82f6',
        //     'order' => 1,
        //     'meta' => [
        //         'reports' => ['completion_rate', 'time_tracking', 'burndown'],
        //         'permissions' => ['assign_tasks', 'change_status', 'log_time'],
        //     ],
        // ]);

        // // Lead type configuration
        // $service->registerType([
        //     'name' => 'lead',
        //     'display_name' => 'Leads',
        //     // 'model_class' => \Modules\Deal\Models\Lead::class,
        //     'has_value' => true,
        //     'has_probability' => true,
        //     'custom_fields' => [
        //         'company' => [
        //             'type' => 'text',
        //             'label' => 'Company',
        //             'required' => false,
        //             'validation' => 'nullable|string|max:255',
        //         ],
        //         'phone' => [
        //             'type' => 'text',
        //             'label' => 'Phone',
        //             'required' => false,
        //             'validation' => 'nullable|string|max:20',
        //         ],
        //         'email' => [
        //             'type' => 'email',
        //             'label' => 'Email',
        //             'required' => false,
        //             'validation' => 'nullable|email|max:255',
        //         ],
        //         'source' => [
        //             'type' => 'select',
        //             'label' => 'Source',
        //             'options' => ['website', 'referral', 'advertisement', 'event', 'social_media', 'outbound'],
        //             'required' => false,
        //             'validation' => 'nullable|string|in:website,referral,advertisement,event,social_media,outbound',
        //         ],
        //         'industry' => [
        //             'type' => 'select',
        //             'label' => 'Industry',
        //             'options' => ['technology', 'healthcare', 'finance', 'education', 'retail', 'manufacturing'],
        //             'required' => false,
        //             'validation' => 'nullable|string',
        //         ],
        //     ],
        //     'validations' => [
        //         'email' => 'nullable|email|max:255',
        //         'phone' => 'nullable|string|max:20',
        //         'company' => 'nullable|string|max:255',
        //         'source' => 'nullable|string|in:website,referral,advertisement,event,social_media,outbound',
        //     ],
        //     'default_stages' => [
        //         [
        //             'name' => 'New',
        //             'color' => '#f59e0b',
        //             'order' => 0,
        //             'probability' => 10,
        //             'meta' => ['is_new' => true]
        //         ],
        //         [
        //             'name' => 'Contacted',
        //             'color' => '#10b981',
        //             'order' => 1,
        //             'probability' => 30,
        //             'meta' => ['is_contacted' => true]
        //         ],
        //         [
        //             'name' => 'Qualified',
        //             'color' => '#3b82f6',
        //             'order' => 2,
        //             'probability' => 50,
        //             'meta' => ['is_qualified' => true]
        //         ],
        //         [
        //             'name' => 'Proposal Sent',
        //             'color' => '#8b5cf6',
        //             'order' => 3,
        //             'probability' => 70,
        //             'meta' => ['proposal_sent' => true]
        //         ],
        //         [
        //             'name' => 'Converted to Deal',
        //             'color' => '#047857',
        //             'order' => 4,
        //             'probability' => 100,
        //             'meta' => ['is_converted' => true]
        //         ],
        //         [
        //             'name' => 'Disqualified',
        //             'color' => '#ef4444',
        //             'order' => 5,
        //             'probability' => 0,
        //             'meta' => ['is_disqualified' => true]
        //         ],
        //     ],
        //     'icon' => 'heroicon-o-user-group',
        //     'color' => '#8b5cf6',
        //     'order' => 2,
        //     'meta' => [
        //         'reports' => ['conversion_rate', 'source_analysis', 'lead_quality'],
        //         'permissions' => ['import_leads', 'export_leads', 'assign_leads'],
        //     ],
        // ]);

        // // Ticket type configuration
        // $service->registerType([
        //     'name' => 'ticket',
        //     'display_name' => 'Tickets',
        //     // 'model_class' => \App\Models\Ticket::class,
        //     'has_priority' => true,
        //     'has_due_date' => true,
        //     'custom_fields' => [
        //         'category' => [
        //             'type' => 'select',
        //             'label' => 'Category',
        //             'options' => ['technical', 'billing', 'general', 'feature_request', 'bug'],
        //             'required' => true,
        //             'validation' => 'required|string|in:technical,billing,general,feature_request,bug',
        //         ],
        //         'severity' => [
        //             'type' => 'select',
        //             'label' => 'Severity',
        //             'options' => ['low', 'medium', 'high', 'critical'],
        //             'required' => true,
        //             'validation' => 'required|string|in:low,medium,high,critical',
        //         ],
        //         'assignee_id' => [
        //             'type' => 'user',
        //             'label' => 'Assignee',
        //             'required' => false,
        //             'validation' => 'nullable|exists:users,id',
        //         ],
        //         'customer_id' => [
        //             'type' => 'customer',
        //             'label' => 'Customer',
        //             'required' => true,
        //             'validation' => 'required|exists:customers,id',
        //         ],
        //     ],
        //     'validations' => [
        //         'category' => 'required|string|in:technical,billing,general,feature_request,bug',
        //         'severity' => 'required|string|in:low,medium,high,critical',
        //         'assignee_id' => 'nullable|exists:users,id',
        //         'customer_id' => 'required|exists:customers,id',
        //     ],
        //     'default_stages' => [
        //         [
        //             'name' => 'New',
        //             'color' => '#f59e0b',
        //             'order' => 0,
        //             'meta' => ['is_new' => true]
        //         ],
        //         [
        //             'name' => 'Open',
        //             'color' => '#3b82f6',
        //             'order' => 1,
        //             'meta' => ['is_open' => true]
        //         ],
        //         [
        //             'name' => 'Pending',
        //             'color' => '#8b5cf6',
        //             'order' => 2,
        //             'meta' => ['is_pending' => true]
        //         ],
        //         [
        //             'name' => 'Resolved',
        //             'color' => '#10b981',
        //             'order' => 3,
        //             'meta' => ['is_resolved' => true]
        //         ],
        //         [
        //             'name' => 'Closed',
        //             'color' => '#6b7280',
        //             'order' => 4,
        //             'meta' => ['is_closed' => true]
        //         ],
        //     ],
        //     'icon' => 'heroicon-o-lifebuoy',
        //     'color' => '#f59e0b',
        //     'order' => 3,
        //     'meta' => [
        //         'reports' => ['sla_compliance', 'resolution_time', 'ticket_volume'],
        //         'permissions' => ['assign_tickets', 'close_tickets', 'escalate_tickets'],
        //     ],
        // ]);

        // // Support type configuration
        // $service->registerType([
        //     'name' => 'support',
        //     'display_name' => 'Support Cases',
        //     // 'model_class' => \App\Models\SupportCase::class,
        //     'has_priority' => true,
        //     'has_due_date' => true,
        //     'custom_fields' => [
        //         'case_type' => [
        //             'type' => 'select',
        //             'label' => 'Case Type',
        //             'options' => ['general', 'technical', 'billing', 'refund', 'complaint'],
        //             'required' => true,
        //             'validation' => 'required|string|in:general,technical,billing,refund,complaint',
        //         ],
        //         'customer_tier' => [
        //             'type' => 'select',
        //             'label' => 'Customer Tier',
        //             'options' => ['free', 'basic', 'pro', 'enterprise'],
        //             'required' => true,
        //             'validation' => 'required|string|in:free,basic,pro,enterprise',
        //         ],
        //         'sla_level' => [
        //             'type' => 'select',
        //             'label' => 'SLA Level',
        //             'options' => ['standard', 'priority', 'urgent'],
        //             'required' => true,
        //             'validation' => 'required|string|in:standard,priority,urgent',
        //         ],
        //         'assigned_team' => [
        //             'type' => 'select',
        //             'label' => 'Assigned Team',
        //             'options' => ['support', 'technical', 'billing', 'escalations'],
        //             'required' => false,
        //             'validation' => 'nullable|string|in:support,technical,billing,escalations',
        //         ],
        //     ],
        //     'validations' => [
        //         'case_type' => 'required|string|in:general,technical,billing,refund,complaint',
        //         'customer_tier' => 'required|string|in:free,basic,pro,enterprise',
        //         'sla_level' => 'required|string|in:standard,priority,urgent',
        //         'assigned_team' => 'nullable|string|in:support,technical,billing,escalations',
        //     ],
        //     'default_stages' => [
        //         [
        //             'name' => 'Received',
        //             'color' => '#f59e0b',
        //             'order' => 0,
        //             'meta' => ['is_received' => true]
        //         ],
        //         [
        //             'name' => 'Investigating',
        //             'color' => '#3b82f6',
        //             'order' => 1,
        //             'meta' => ['is_investigating' => true]
        //         ],
        //         [
        //             'name' => 'Awaiting Response',
        //             'color' => '#8b5cf6',
        //             'order' => 2,
        //             'meta' => ['awaiting_response' => true]
        //         ],
        //         [
        //             'name' => 'Solution Proposed',
        //             'color' => '#10b981',
        //             'order' => 3,
        //             'meta' => ['solution_proposed' => true]
        //         ],
        //         [
        //             'name' => 'Verified',
        //             'color' => '#047857',
        //             'order' => 4,
        //             'meta' => ['is_verified' => true]
        //         ],
        //         [
        //             'name' => 'Archived',
        //             'color' => '#6b7280',
        //             'order' => 5,
        //             'meta' => ['is_archived' => true]
        //         ],
        //     ],
        //     'icon' => 'heroicon-o-chat-alt-2',
        //     'color' => '#ec4899',
        //     'order' => 4,
        //     'meta' => [
        //         'reports' => ['case_volume', 'resolution_rate', 'customer_satisfaction'],
        //         'permissions' => ['escalate_cases', 'view_all_cases', 'manage_sla'],
        //     ],
        // ]);

        // // Opportunity type configuration (additional example)
        // $service->registerType([
        //     'name' => 'opportunity',
        //     'display_name' => 'Opportunities',
        //     // 'model_class' => \App\Models\Opportunity::class,
        //     'has_probability' => true,
        //     'has_value' => true,
        //     'has_due_date' => true,
        //     'has_priority' => true,
        //     'custom_fields' => [
        //         'forecast_amount' => [
        //             'type' => 'number',
        //             'label' => 'Forecast Amount',
        //             'required' => false,
        //             'validation' => 'nullable|numeric|min:0',
        //         ],
        //         'next_step' => [
        //             'type' => 'text',
        //             'label' => 'Next Step',
        //             'required' => false,
        //             'validation' => 'nullable|string|max:500',
        //         ],
        //         'competitors' => [
        //             'type' => 'tags',
        //             'label' => 'Competitors',
        //             'required' => false,
        //             'validation' => 'nullable|array',
        //         ],
        //         'decision_maker' => [
        //             'type' => 'text',
        //             'label' => 'Decision Maker',
        //             'required' => false,
        //             'validation' => 'nullable|string|max:255',
        //         ],
        //     ],
        //     'default_stages' => [
        //         [
        //             'name' => 'Identification',
        //             'color' => '#f59e0b',
        //             'order' => 0,
        //             'probability' => 20,
        //             'meta' => ['is_identified' => true]
        //         ],
        //         [
        //             'name' => 'Development',
        //             'color' => '#3b82f6',
        //             'order' => 1,
        //             'probability' => 40,
        //             'meta' => ['in_development' => true]
        //         ],
        //         [
        //             'name' => 'Proposal',
        //             'color' => '#8b5cf6',
        //             'order' => 2,
        //             'probability' => 60,
        //             'meta' => ['proposal_prepared' => true]
        //         ],
        //         [
        //             'name' => 'Negotiation',
        //             'color' => '#ec4899',
        //             'order' => 3,
        //             'probability' => 80,
        //             'meta' => ['in_negotiation' => true]
        //         ],
        //         [
        //             'name' => 'Won',
        //             'color' => '#10b981',
        //             'order' => 4,
        //             'probability' => 100,
        //             'meta' => ['is_won' => true]
        //         ],
        //         [
        //             'name' => 'Lost',
        //             'color' => '#ef4444',
        //             'order' => 5,
        //             'probability' => 0,
        //             'meta' => ['is_lost' => true]
        //         ],
        //     ],
        //     'icon' => 'heroicon-o-trending-up',
        //     'color' => '#f97316',
        //     'order' => 5,
        //     'meta' => [
        //         'reports' => ['pipeline_value', 'win_rate', 'average_sales_cycle'],
        //         'permissions' => ['forecast_opportunities', 'close_opportunities'],
        //     ],
        // ]);
    }

    /**
     * Get all registered type names.
     *
     * @return array
     */
    public static function getTypeNames(): array
    {
        return [
            'deal',
            // 'task',
            // 'lead',
            // 'ticket',
            // 'support',
            // 'opportunity',
        ];
    }

    /**
     * Get type configuration by name.
     *
     * @param string $typeName
     * @return array|null
     */
    public static function getTypeConfig(string $typeName): ?array
    {
        $configs = [
            'deal' => [
                'icon' => 'heroicon-o-currency-dollar',
                'color' => '#10b981',
                'has_probability' => true,
            ],
            // 'task' => [
            //     'icon' => 'heroicon-o-check-circle',
            //     'color' => '#3b82f6',
            //     'has_due_date' => true,
            // ],
            // 'lead' => [
            //     'icon' => 'heroicon-o-user-group',
            //     'color' => '#8b5cf6',
            //     'has_probability' => true,
            // ],
            // 'ticket' => [
            //     'icon' => 'heroicon-o-lifebuoy',
            //     'color' => '#f59e0b',
            //     'has_priority' => true,
            // ],
            // 'support' => [
            //     'icon' => 'heroicon-o-chat-alt-2',
            //     'color' => '#ec4899',
            //     'has_priority' => true,
            // ],
            // 'opportunity' => [
            //     'icon' => 'heroicon-o-trending-up',
            //     'color' => '#f97316',
            //     'has_probability' => true,
            // ],
        ];

        return $configs[$typeName] ?? null;
    }
}
