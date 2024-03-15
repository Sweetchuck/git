<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\StdInputReader\Item;

abstract class BaseItem implements \Stringable
{

    protected string $separator = ' ';

    /**
     * @return string[]
     */
    abstract protected function getPropertyValues(): array;

    /**
     * {@inheritdoc}
     */
    public function __toString(): string
    {
        return implode($this->separator, $this->getPropertyValues());
    }
}
