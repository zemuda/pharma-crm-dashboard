<?php

namespace Modules\Core\Enums;

use Modules\Core\Enums\BaseEnumTrait;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

enum PriorityEnum: string implements Arrayable, JsonSerializable
{
    use BaseEnumTrait;

    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Urgent = 'urgent';
    case Critical = 'critical';

    public function description(): string
    {
        return match ($this) {
            self::Low => 'Low importance — can be handled later',
            self::Medium => 'Normal priority — should be addressed soon',
            self::High => 'High importance — handle promptly',
            self::Urgent => 'Urgent — requires immediate attention',
            self::Critical => 'Critical — business-impacting issue',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Low => 'text-green-500',
            self::Medium => 'text-blue-500',
            self::High => 'text-orange-500',
            self::Urgent => 'text-red-500',
            self::Critical => 'text-rose-600',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Low => 'arrow-down-circle',
            self::Medium => 'circle',
            self::High => 'arrow-up-circle',
            self::Urgent => 'alert-triangle',
            self::Critical => 'zap',
        };
    }
}
