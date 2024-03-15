<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFetchTrait
{
    protected function initPropertyFetch(): static
    {
        $this->properties['commandOptions']['fetch'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFetch(array $properties): static
    {
        if (array_key_exists('fetch', $properties)) {
            $this->setFetch($properties['fetch']);
        }

        return $this;
    }

    public function getFetch(): ?bool
    {
        return $this->properties['commandOptions']['fetch']['state'];
    }

    public function setFetch(?bool $value): static
    {
        $this->properties['commandOptions']['fetch']['state'] = $value;

        return $this;
    }
}
