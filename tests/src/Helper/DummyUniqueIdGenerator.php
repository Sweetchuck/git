<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Helper;

class DummyUniqueIdGenerator
{

    /**
     * @var array<string>
     */
    public array $ids = [
        '×',
        '¤',
        'Ä',
        '®',
        'Đ',
        '€',
        'ß',
        'ä',
    ];

    public function __invoke(): string
    {
        if (!$this->ids) {
            throw new \LogicException();
        }

        return array_pop($this->ids);
    }
}
