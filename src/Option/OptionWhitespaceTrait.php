<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionWhitespaceTrait
{

    protected function initPropertyWhitespace(): static
    {
        $this->properties['commandOptions']['whitespace'] = [
            'type' => 'value:false:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyWhitespace(array $properties): static
    {
        if (array_key_exists('whitespace', $properties)) {
            $this->setWhitespace($properties['whitespace']);
        }

        return $this;
    }

    public function getWhitespace(): null|false|string
    {
        return $this->properties['commandOptions']['whitespace']['value'];
    }

    public function setWhitespace(null|false|string $value): static
    {
        $this->properties['commandOptions']['whitespace']['value'] = $value;

        return $this;
    }
}
