<?php

namespace Modules\Core\Enums;

use Modules\Core\Enums\BaseEnumTrait;
use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

enum RecurrenceTypeEnum: string implements Arrayable, JsonSerializable
{
    use BaseEnumTrait;

    case None = 'none';
    case Daily = 'daily';
    case Weekly = 'weekly';
    case Monthly = 'monthly';
    case Quarterly = 'quarterly';
    case Yearly = 'yearly';
    case Custom = 'custom';

    public function description(): string
    {
        return match ($this) {
            self::None => 'One-time activity with no repetition',
            self::Daily => 'Repeats every day',
            self::Weekly => 'Repeats weekly on selected days',
            self::Monthly => 'Repeats on specific days each month',
            self::Quarterly => 'Repeats every three months',
            self::Yearly => 'Repeats annually',
            self::Custom => 'Custom recurrence pattern',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::None => 'text-gray-400',
            self::Daily => 'text-green-500',
            self::Weekly => 'text-blue-500',
            self::Monthly => 'text-purple-500',
            self::Quarterly => 'text-teal-500',
            self::Yearly => 'text-yellow-500',
            self::Custom => 'text-pink-500',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::None => 'x-circle',
            self::Daily => 'sun',
            self::Weekly => 'calendar',
            self::Monthly => 'calendar-days',
            self::Quarterly => 'refresh-cw',
            self::Yearly => 'repeat',
            self::Custom => 'settings',
        };
    }
}
