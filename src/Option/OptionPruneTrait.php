<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPruneTrait
{
    protected function initPropertyPrune(): static
    {
        $this->properties['commandOptions']['prune'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPrune(array $properties): static
    {
        if (array_key_exists('prune', $properties)) {
            $this->setPrune($properties['prune']);
        }

        return $this;
    }

    public function getPrune(): ?bool
    {
        return $this->properties['commandOptions']['prune']['state'];
    }

    public function setPrune(?bool $value): static
    {
        $this->properties['commandOptions']['prune']['state'] = $value;

        return $this;
    }
}
