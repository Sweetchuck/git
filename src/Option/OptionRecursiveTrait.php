<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRecursiveTrait
{
    protected function initPropertyRecursive(): static
    {
        $this->properties['commandOptions']['recursive'] = [
            'type' => CommandOptionType::StateTrue,
            'name' => '-r',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRecursive(array $properties): static
    {
        if (array_key_exists('recursive', $properties)) {
            $this->setRecursive($properties['recursive']);
        }

        return $this;
    }

    public function getRecursive(): ?bool
    {
        return $this->properties['commandOptions']['recursive']['state'];
    }

    public function setRecursive(?bool $value): static
    {
        $this->properties['commandOptions']['recursive']['state'] = $value;

        return $this;
    }
}
