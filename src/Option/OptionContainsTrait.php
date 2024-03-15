<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionContainsTrait
{

    protected function initPropertyContains(): static
    {
        $this->properties['commandOptions']['contains'] = [
            'type' => 'state:string-required:multi',
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyContains(array $properties): static
    {
        if (array_key_exists('contains', $properties)) {
            $this->setContains($properties['contains']);
        }

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    public function getContains(): array
    {
        return $this->properties['commandOptions']['contains']['value'];
    }

    /**
     * @param array<string, bool> $value
     */
    public function setContains(array $value): static
    {
        $this->properties['commandOptions']['contains']['value'] = $value;

        return $this;
    }
}
