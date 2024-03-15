<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class CommandBase
{

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): static
    {
        return $this;
    }
}
