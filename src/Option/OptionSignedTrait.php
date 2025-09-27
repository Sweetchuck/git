<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSignedTrait
{
    protected function initPropertySigned(): static
    {
        $this->properties['commandOptions']['signed'] = [
            'type' => 'value:true-false:string',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySigned(array $properties): static
    {
        if (array_key_exists('signed', $properties)) {
            $this->setSigned($properties['signed']);
        }

        return $this;
    }

    public function getSigned(): null|bool|string
    {
        return $this->properties['commandOptions']['signed']['value'];
    }

    public function setSigned(null|bool|string $value): static
    {
        $this->properties['commandOptions']['signed']['value'] = $value;

        return $this;
    }
}
