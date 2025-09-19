<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAddTrait
{

    protected function initPropertyAdd(): static
    {
        $this->properties['commandOptions']['add'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAdd(array $properties): static
    {
        if (array_key_exists('add', $properties)) {
            $this->setAdd($properties['add']);
        }

        return $this;
    }

    public function getAdd(): ?bool
    {
        return $this->properties['commandOptions']['add']['state'];
    }

    public function setAdd(?bool $value): static
    {
        $this->properties['commandOptions']['add']['state'] = $value;

        return $this;
    }
}
