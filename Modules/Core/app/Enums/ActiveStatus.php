<?php

namespace Modules\Core\Enums;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

enum ActiveStatus: string implements Arrayable, JsonSerializable
{
    case Active = 'active';
    case Inactive = 'inactive';
    case Pending = 'pending';
    case Discontinued = 'discontinued';
    case Deleted = 'deleted';
    case Draft = 'draft';
    case Archived = 'archived';

    public function description(): string
    {
        return match ($this) {
            self::Active => 'Active status with full access',
            self::Inactive => 'Status not currently active',
            self::Pending => 'Awaiting activation or approval',
            self::Discontinued => 'Temporarily disabled',
            self::Deleted => 'Soft-deleted',
            self::Draft => 'Unpublished draft state',
            self::Archived => 'Permanently archived record',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Active => 'text-green-500',
            self::Inactive => 'text-gray-400',
            self::Pending => 'text-yellow-500',
            self::Discontinued => 'text-red-500',
            self::Deleted => 'text-gray-600',
            self::Draft => 'text-blue-400',
            self::Archived => 'text-purple-500',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::Active => 'check-circle',
            self::Inactive => 'pause-circle',
            self::Pending => 'clock',
            self::Discontinued => 'slash-circle',
            self::Deleted => 'trash-2',
            self::Draft => 'edit',
            self::Archived => 'archive',
        };
    }

    public function isActiveState(): bool
    {
        return in_array($this, [self::Active, self::Pending]);
    }

    public static function activeStates(): array
    {
        return [self::Active, self::Pending];
    }

    public static function disabledStates(): array
    {
        return [self::Inactive, self::Discontinued, self::Archived];
    }


    public static function forSelect(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => strtoupper($case->value),
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

    public static function random(): self
    {
        return collect(self::cases())->random();
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
