<?php

namespace Modules\Core\Enums;

enum LevelOfUse: int
{
    case COMMUNITY = 1;
    case DISPENSARY = 2;
    case HEALTH_CENTRE = 3;
    case PRIMARY_HOSPITAL = 4;
    case SECONDARY_HOSPITAL = 5;
    case TERTIARY_HOSPITAL = 6;

    /**
     * Get the label for display.
     */
    public function label(): string
    {
        return match ($this) {
            self::COMMUNITY => 'Community Health Services',
            self::DISPENSARY => 'Dispensary / Clinic',
            self::HEALTH_CENTRE => 'Health Centre',
            self::PRIMARY_HOSPITAL => 'Primary Hospital',
            self::SECONDARY_HOSPITAL => 'Secondary Hospital',
            self::TERTIARY_HOSPITAL => 'Tertiary Hospital',
        };
    }

    /**
     * Get a description or definition for each level.
     */
    public function description(): string
    {
        return match ($this) {
            self::COMMUNITY => 'Lowest-level services, typically without a pharmacy or full-time health worker.',
            self::DISPENSARY => 'Basic health services, including essential medicine dispensing.',
            self::HEALTH_CENTRE => 'More comprehensive services including diagnosis and treatment.',
            self::PRIMARY_HOSPITAL => 'Basic hospital with limited inpatient capacity.',
            self::SECONDARY_HOSPITAL => 'Regional hospital with specialist services.',
            self::TERTIARY_HOSPITAL => 'Major referral hospital with advanced diagnostic and treatment facilities.',
        };
    }

    /**
     * Get all values as array for select boxes etc.
     */
    public static function options(): array
    {
        return array_map(
            fn($case) => [
                'value' => $case->value,
                'label' => $case->label(),
            ],
            self::cases()
        );
    }
}
