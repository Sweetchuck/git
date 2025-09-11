<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUntilTrait
{
    protected function initPropertyUntil(): static
    {
        $this->properties['commandOptions']['until'] = [
            'type' => 'value:string-required',
            'name' => '--until',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUntil(array $properties): static
    {
        if (array_key_exists('until', $properties)) {
            $this->setUntil($properties['until']);
        }

        return $this;
    }

    public function getUntil(): ?string
    {
        return $this->properties['commandOptions']['until']['value'];
    }

    public function setUntil(?string $value): static
    {
        $this->properties['commandOptions']['until']['value'] = $value;

        return $this;
    }
}
