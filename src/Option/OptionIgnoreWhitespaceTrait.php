<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreWhitespaceTrait
{

    protected function initPropertyIgnoreWhitespace(): static
    {
        $this->properties['commandOptions']['ignoreWhitespace'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreWhitespace(array $properties): static
    {
        if (array_key_exists('ignoreWhitespace', $properties)) {
            $this->setIgnoreWhitespace($properties['ignoreWhitespace']);
        }

        return $this;
    }

    public function getIgnoreWhitespace(): ?bool
    {
        return $this->properties['commandOptions']['ignoreWhitespace']['state'];
    }

    public function setIgnoreWhitespace(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreWhitespace']['state'] = $value;

        return $this;
    }
}
