<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSparseTrait
{
    protected function initPropertySparse(): static
    {
        $this->properties['commandOptions']['sparse'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySparse(array $properties): static
    {
        if (array_key_exists('sparse', $properties)) {
            $this->setSparse($properties['sparse']);
        }

        return $this;
    }

    public function getSparse(): ?bool
    {
        return $this->properties['commandOptions']['sparse']['state'];
    }

    public function setSparse(?bool $value): static
    {
        $this->properties['commandOptions']['sparse']['state'] = $value;

        return $this;
    }
}
