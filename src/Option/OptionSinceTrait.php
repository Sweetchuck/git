<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSinceTrait
{
    protected function initPropertySince(): static
    {
        $this->properties['commandOptions']['since'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySince(array $properties): static
    {
        if (array_key_exists('since', $properties)) {
            $this->setSince($properties['since']);
        }

        return $this;
    }

    public function getSince(): ?string
    {
        return $this->properties['commandOptions']['since']['value'];
    }

    public function setSince(?string $value): static
    {
        $this->properties['commandOptions']['since']['value'] = $value;

        return $this;
    }
}
