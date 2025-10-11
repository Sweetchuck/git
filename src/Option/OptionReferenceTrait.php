<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionReferenceTrait
{
    protected function initPropertyReference(): static
    {
        $this->properties['commandOptions']['reference'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyReference(array $properties): static
    {
        if (array_key_exists('reference', $properties)) {
            $this->setReference($properties['reference']);
        }

        return $this;
    }

    public function getReference(): null|false|string
    {
        return $this->properties['commandOptions']['reference']['value'];
    }

    public function setReference(null|false|string $value): static
    {
        $this->properties['commandOptions']['reference']['value'] = $value;

        return $this;
    }
}
