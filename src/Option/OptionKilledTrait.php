<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionKilledTrait
{
    protected function initPropertyKilled(): static
    {
        $this->properties['commandOptions']['killed'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyKilled(array $properties): static
    {
        if (array_key_exists('killed', $properties)) {
            $this->setKilled($properties['killed']);
        }

        return $this;
    }

    public function getKilled(): ?bool
    {
        return $this->properties['commandOptions']['killed']['state'];
    }

    public function setKilled(?bool $value): static
    {
        $this->properties['commandOptions']['killed']['state'] = $value;

        return $this;
    }
}
