<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUnshallowTrait
{

    protected function initPropertyUnshallow(): static
    {
        $this->properties['commandOptions']['unshallow'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUnshallow(array $properties): static
    {
        if (array_key_exists('unshallow', $properties)) {
            $this->setUnshallow($properties['unshallow']);
        }

        return $this;
    }

    public function getUnshallow(): ?bool
    {
        return $this->properties['commandOptions']['unshallow']['state'];
    }

    public function setUnshallow(?bool $value): static
    {
        $this->properties['commandOptions']['unshallow']['state'] = $value;

        return $this;
    }
}
