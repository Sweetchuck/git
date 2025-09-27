<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionThinTrait
{

    protected function initPropertyThin(): static
    {
        $this->properties['commandOptions']['thin'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyThin(array $properties): static
    {
        if (array_key_exists('thin', $properties)) {
            $this->setThin($properties['thin']);
        }

        return $this;
    }

    public function getThin(): ?bool
    {
        return $this->properties['commandOptions']['thin']['state'];
    }

    public function setThin(?bool $value): static
    {
        $this->properties['commandOptions']['thin']['state'] = $value;

        return $this;
    }
}
