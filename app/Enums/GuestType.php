<?php

namespace App\Enums;

enum GuestType: string
{
    case Child = 'child';
    case Male = 'male';
    case Female = 'female';
    case Diverse = 'diverse';

    public static function values(): array
    {
        return array_map(fn(GuestType $type) => $type->value, GuestType::cases());
    }

    public static function adultTypes(): array
    {
        return [
            self::Male->value,
            self::Female->value,
            self::Diverse->value,
        ];
    }

    public static function childTypes(): array
    {
        return [
            self::Child->value,
        ];
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::Child => __('Child'),
            self::Male => __('Male'),
            self::Female => __('Female'),
            self::Diverse => __('Diverse'),
        };
    }
}
