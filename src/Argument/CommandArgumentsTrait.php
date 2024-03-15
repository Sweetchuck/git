<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Argument;

/**
 * @property array<string, mixed> $properties
 */
trait CommandArgumentsTrait
{
    /**
     * @return array<mixed>
     */
    public function getCommandArguments(): array
    {
        return $this->properties['commandArguments'];
    }

    /**
     * @param array<mixed> $args
     */
    public function setCommandArguments(array $args): static
    {
        $this->properties['commandArguments'] = $args;

        return $this;
    }

    public function addCommandArgument(string $arg): static
    {
        $this->properties['commandArguments'][] = $arg;

        return $this;
    }
}
