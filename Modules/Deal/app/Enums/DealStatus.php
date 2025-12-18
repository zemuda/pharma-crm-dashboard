<?php

namespace Modules\Deal\Enums;

use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

enum DealStatus: string implements Arrayable, Jsonable
{
    case DRAFT = 'draft';
    case PENDING_APPROVAL = 'pending_approval';
    case ACTIVE = 'active';
    case NEGOTIATION = 'negotiation';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';
    case EXPIRED = 'expired';
    case SUSPENDED = 'suspended';
    case ARCHIVED = 'archived';
    case CLOSED_WON = 'closed_won';
    case CLOSED_LOST = 'closed_lost';

    // Color mapping for UI
    public function color(): string
    {
        return match ($this) {
            self::DRAFT => 'gray',
            self::PENDING_APPROVAL => 'yellow',
            self::ACTIVE => 'green',
            self::NEGOTIATION => 'blue',
            self::APPROVED => 'teal',
            self::REJECTED => 'red',
            self::EXPIRED => 'orange',
            self::SUSPENDED => 'purple',
            self::ARCHIVED => 'slate',
            self::CLOSED_WON => 'emerald',
            self::CLOSED_LOST => 'rose',
        };
    }

    // Icon mapping for UI
    public function icon(): string
    {
        return match ($this) {
            self::DRAFT => 'document-text',
            self::PENDING_APPROVAL => 'clock',
            self::ACTIVE => 'check-circle',
            self::NEGOTIATION => 'arrows-right-left',
            self::APPROVED => 'shield-check',
            self::REJECTED => 'x-circle',
            self::EXPIRED => 'calendar-days',
            self::SUSPENDED => 'pause-circle',
            self::ARCHIVED => 'archive-box',
            self::CLOSED_WON => 'trophy',
            self::CLOSED_LOST => 'face-frown',
        };
    }

    // Human readable label
    public function label(): string
    {
        return match ($this) {
            self::DRAFT => 'Draft',
            self::PENDING_APPROVAL => 'Pending Approval',
            self::ACTIVE => 'Active',
            self::NEGOTIATION => 'In Negotiation',
            self::APPROVED => 'Approved',
            self::REJECTED => 'Rejected',
            self::EXPIRED => 'Expired',
            self::SUSPENDED => 'Suspended',
            self::ARCHIVED => 'Archived',
            self::CLOSED_WON => 'Closed Won',
            self::CLOSED_LOST => 'Closed Lost',
        };
    }

    // Status groups/categories
    public function category(): string
    {
        return match ($this) {
            self::DRAFT,
            self::PENDING_APPROVAL => 'preparation',
            self::ACTIVE,
            self::NEGOTIATION => 'active',
            self::APPROVED => 'approved',
            self::REJECTED,
            self::CLOSED_LOST => 'lost',
            self::EXPIRED,
            self::SUSPENDED => 'inactive',
            self::ARCHIVED => 'archived',
            self::CLOSED_WON => 'won',
        };
    }

    // Check if status is in a specific group
    public function isActive(): bool
    {
        return in_array($this, [self::ACTIVE, self::NEGOTIATION]);
    }

    public function isClosed(): bool
    {
        return in_array($this, [self::CLOSED_WON, self::CLOSED_LOST]);
    }

    public function isWon(): bool
    {
        return $this === self::CLOSED_WON;
    }

    public function isLost(): bool
    {
        return $this === self::CLOSED_LOST;
    }

    public function isPending(): bool
    {
        return in_array($this, [self::PENDING_APPROVAL, self::NEGOTIATION]);
    }

    // Allowed status transitions
    public function allowedTransitions(): array
    {
        return match ($this) {
            self::DRAFT => [self::PENDING_APPROVAL, self::ACTIVE, self::ARCHIVED],
            self::PENDING_APPROVAL => [self::APPROVED, self::REJECTED, self::ACTIVE],
            self::ACTIVE => [
                self::NEGOTIATION,
                self::SUSPENDED,
                self::EXPIRED,
                self::CLOSED_WON,
                self::CLOSED_LOST,
                self::ARCHIVED
            ],
            self::NEGOTIATION => [self::ACTIVE, self::CLOSED_WON, self::CLOSED_LOST],
            self::APPROVED => [self::ACTIVE, self::ARCHIVED],
            self::REJECTED => [self::DRAFT, self::ARCHIVED],
            self::EXPIRED => [self::ACTIVE, self::ARCHIVED],
            self::SUSPENDED => [self::ACTIVE, self::ARCHIVED],
            self::ARCHIVED => [self::DRAFT, self::ACTIVE],
            // Before (Incorrect syntax for match expression)
            // self::CLOSED_WON, self::CLOSED_LOST => [self::ARCHIVED],

            // After (Correct syntax for match expression)
            self::CLOSED_WON => [self::ARCHIVED],
            self::CLOSED_LOST => [self::ARCHIVED],
        };
    }

    // Check if transition is allowed
    public function canTransitionTo(self $status): bool
    {
        return in_array($status, $this->allowedTransitions());
    }

    // Get next recommended statuses
    public function nextRecommendedStatuses(): array
    {
        return match ($this) {
            self::DRAFT => [self::PENDING_APPROVAL],
            self::PENDING_APPROVAL => [self::APPROVED, self::ACTIVE],
            self::ACTIVE => [self::NEGOTIATION, self::CLOSED_WON],
            self::NEGOTIATION => [self::CLOSED_WON, self::CLOSED_LOST],
            default => [],
        };
    }

    // Get all statuses as array for forms/selects
    public static function all(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
            'color' => $case->color(),
            'icon' => $case->icon(),
            'category' => $case->category(),
        ], self::cases());
    }

    public static function forSelect(): array
    {
        return Collection::make(self::cases())
            ->mapWithKeys(fn($status) => [
                $status->value => $status->label()
            ])
            ->toArray();
    }

    public static function groupedForSelect(): array
    {
        $grouped = [];

        foreach (self::cases() as $status) {
            $grouped[$status->category()][$status->value] = $status->label();
        }

        return $grouped;
    }

    // Implement Arrayable interface
    public function toArray(): array
    {
        return [
            'value' => $this->value,
            'label' => $this->label(),
            'color' => $this->color(),
            'icon' => $this->icon(),
            'category' => $this->category(),
            'is_active' => $this->isActive(),
            'is_closed' => $this->isClosed(),
            'is_pending' => $this->isPending(),
            'allowed_transitions' => array_map(
                fn($s) => $s->value,
                $this->allowedTransitions()
            ),
        ];
    }

    // Implement Jsonable interface
    public function toJson($options = 0): string
    {
        return json_encode($this->toArray(), $options);
    }

    // Get all statuses by category
    public static function getByCategory(string $category): array
    {
        return array_filter(
            self::cases(),
            fn($status) => $status->category() === $category
        );
    }

    // Get workflow statuses (non-final)
    public static function workflowStatuses(): array
    {
        return array_filter(
            self::cases(),
            fn($status) => !$status->isClosed()
        );
    }

    // Get final statuses
    public static function finalStatuses(): array
    {
        return [self::CLOSED_WON, self::CLOSED_LOST, self::ARCHIVED];
    }

    // Check if status is final
    public function isFinal(): bool
    {
        return in_array($this, self::finalStatuses());
    }

    // Get badge class for UI
    public function badgeClass(): string
    {
        $color = $this->color();
        return "bg-{$color}-100 text-{$color}-800 dark:bg-{$color}-900 dark:text-{$color}-300";
    }

    // Progress percentage (for progress bars)
    // public function progress_1(): int
    // {
    //     $progressMap = [
    //         self::DRAFT => 0,
    //         self::PENDING_APPROVAL => 20,
    //         self::ACTIVE => 40,
    //         self::NEGOTIATION => 60,
    //         self::APPROVED => 70,
    //         self::EXPIRED => 80,
    //         self::CLOSED_WON => 100,
    //         self::CLOSED_LOST => 100,
    //         self::ARCHIVED => 100,
    //         // default => 50,
    //     ];

    //     return $progressMap[$this] ?? 50;
    // }

    public function progress(): int
    {
        $progressMap = [
            self::DRAFT->value => 0,
            self::PENDING_APPROVAL->value => 20,
            self::ACTIVE->value => 40,
            self::NEGOTIATION->value => 60,
            self::APPROVED->value => 70,
            self::EXPIRED->value => 80,
            self::SUSPENDED->value => 50,
            self::ARCHIVED->value => 100,
            self::CLOSED_WON->value => 100,
            self::CLOSED_LOST->value => 100,
            self::REJECTED->value => 30,
        ];

        return $progressMap[$this->value] ?? 50;
    }
}
