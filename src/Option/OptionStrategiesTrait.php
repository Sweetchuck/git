<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionStrategiesTrait
{
    protected function initPropertyStrategies(): static
    {
        $this->properties['commandOptions']['strategies'] = [
            'type' => 'value:strategies',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyStrategies(array $properties): static
    {
        if (array_key_exists('strategies', $properties)) {
            $this->setStrategies($properties['strategies']);
        }

        return $this;
    }

    /**
     * @return null|array<string, array<string, mixed>>
     */
    public function getStrategies(): ?array
    {
        return $this->properties['commandOptions']['strategies']['value'];
    }

    /**
     * @param null|array<string, array<string, mixed>> $value
     */
    public function setStrategies(?array $value): static
    {
        $this->properties['commandOptions']['strategies']['value'] = $value;

        return $this;
    }
}
