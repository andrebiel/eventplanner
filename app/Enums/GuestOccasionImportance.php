<?php

namespace App\Enums;

enum GuestOccasionImportance: string
{
    case LOW = 'low';
    case MEDIUM = 'medium';
    case HIGH = 'high';

    public function getValue(): float
    {
        return match ($this) {
            self::LOW => 0.3,
            self::MEDIUM => 0.5,
            self::HIGH => 1,
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::LOW => __('Importance.low'),
            self::MEDIUM => __('Importance.medium'),
            self::HIGH => __('Importance.high'),
        };
    }
}
