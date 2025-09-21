<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRecurseTrait
{
    protected function initPropertyRecurse(): static
    {
        $this->properties['commandOptions']['recurse'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRecurse(array $properties): static
    {
        if (array_key_exists('recurse', $properties)) {
            $this->setRecurse($properties['recurse']);
        }

        return $this;
    }

    public function getRecurse(): ?bool
    {
        return $this->properties['commandOptions']['recurse']['state'];
    }

    public function setRecurse(?bool $value): static
    {
        $this->properties['commandOptions']['recurse']['state'] = $value;

        return $this;
    }
}
