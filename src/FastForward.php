<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

enum FastForward: string
{
    case No = 'No';

    case Yes = 'Yes';

    case Only = 'Only';

    /**
     * @return array<string, string>
     */
    public static function getCliOptionMapping(): array
    {
        return [
            'No' => '--no-ff',
            'Yes' => '--ff',
            'Only' => '--ff-only',
        ];
    }

    public static function fromCliOption(string $value): self
    {
        return self::from((string) array_search($value, self::getCliOptionMapping(), true));
    }

    public static function tryFromCliOption(string $value): ?self
    {
        return self::tryFrom((string) array_search($value, self::getCliOptionMapping(), true));
    }
}
