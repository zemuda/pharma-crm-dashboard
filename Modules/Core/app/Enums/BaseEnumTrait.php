<?php

namespace Modules\Core\Enums;

use Illuminate\Contracts\Support\Arrayable;
use JsonSerializable;

trait BaseEnumTrait
{
    /**
     * Return all cases as formatted arrays for select inputs.
     */
    public static function forSelect(): array
    {
        return array_map(fn(self $case) => [
            'value' => $case->value,
            'label' => ucfirst(str_replace('_', ' ', $case->value)),
            'description' => method_exists($case, 'description') ? $case->description() : null,
            'color' => method_exists($case, 'color') ? $case->color() : null,
            'icon' => method_exists($case, 'icon') ? $case->icon() : null,
        ], self::cases());
    }

    /**
     * Return all possible enum values.
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Validate if a given string is a valid enum value.
     */
    public static function isValid(string $value): bool
    {
        return in_array($value, self::values());
    }

    /**
     * Get a random enum case.
     */
    public static function random(): self
    {
        return collect(self::cases())->random();
    }

    /**
     * Convert enum case to array.
     */
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'description' => method_exists($this, 'description') ? $this->description() : null,
            'color' => method_exists($this, 'color') ? $this->color() : null,
            'icon' => method_exists($this, 'icon') ? $this->icon() : null,
        ];
    }

    /**
     * Convert enum case to JSON.
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
