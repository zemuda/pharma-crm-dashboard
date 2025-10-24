<?php

namespace Modules\Core\Enums;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

enum FollowUpStatus: string implements Arrayable, JsonSerializable
{
    case Pending = 'pending';
    case Completed = 'completed';
    case Missed = 'missed';
    case Rescheduled = 'rescheduled';
    case Cancelled = 'cancelled';

    public function description(): string
    {
        return match ($this) {
            self::Pending => 'Awaiting action — not yet completed.',
            self::Completed => 'Follow-up has been successfully completed.',
            self::Missed => 'The follow-up was not completed by the due date.',
            self::Rescheduled => 'The follow-up was postponed to a new date.',
            self::Cancelled => 'The follow-up is no longer required.',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Pending => 'text-yellow-500',
            self::Completed => 'text-green-500',
            self::Missed => 'text-red-500',
            self::Rescheduled => 'text-blue-400',
            self::Cancelled => 'text-gray-400',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Pending => 'clock',
            self::Completed => 'check-circle',
            self::Missed => 'alert-circle',
            self::Rescheduled => 'refresh-cw',
            self::Cancelled => 'x-circle',
        };
    }

    public static function forSelect(): array
    {
        return array_map(fn(self $case) => [
            'value' => $case->value,
            'label' => ucfirst($case->value),
            'description' => $case->description(),
            'color' => $case->color(),
            'icon' => $case->icon(),
        ], self::cases());
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function isValid(string $value): bool
    {
        return in_array($value, self::values());
    }

    public static function default(): self
    {
        return self::Pending;
    }

    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'description' => $this->description(),
            'color' => $this->color(),
            'icon' => $this->icon(),
        ];
    }

    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
