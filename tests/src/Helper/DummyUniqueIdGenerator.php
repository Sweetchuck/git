<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Helper;

class DummyUniqueIdGenerator
{

    /**
     * @var array<string>
     */
    public array $ids = [
        'Đ',
        '¶',
        '®',
        '€',
        '÷',
        '×',
        '¤',
    ];

    public function __invoke(): string
    {
        if (!$this->ids) {
            throw new \LogicException();
        }

        return array_pop($this->ids);
    }
}
