<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionBareTrait
{

    protected function initPropertyBare(): static
    {
        $this->properties['commandOptions']['bare'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyBare(array $properties): static
    {
        if (array_key_exists('bare', $properties)) {
            $this->setBare($properties['bare']);
        }

        return $this;
    }

    public function getBare(): ?bool
    {
        return $this->properties['commandOptions']['bare']['state'];
    }

    public function setBare(?bool $value): static
    {
        $this->properties['commandOptions']['bare']['state'] = $value;

        return $this;
    }
}
