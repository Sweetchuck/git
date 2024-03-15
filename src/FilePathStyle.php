<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

enum FilePathStyle: string
{
    case RelativeToTopLevel = 'RelativeToTopLevel';

    case RelativeToWorkingDirectory = 'RelativeToWorkingDirectory';

    case Absolute = 'Absolute';

    /**
     * @return array<string, int>
     */
    public static function getMapping(): array
    {
        return [
            'RelativeToTopLevel' => 1,
            'RelativeToWorkingDirectory' => 2,
            'Absolute' => 3,
        ];
    }

    public static function fromInteger(int $value): self
    {
        return self::from((string) array_search($value, self::getMapping(), true));
    }

    public static function tryFromInteger(int $value): ?self
    {
        return self::tryFrom((string) array_search($value, self::getMapping(), true));
    }
}
