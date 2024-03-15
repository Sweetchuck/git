<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOriginTrait
{
    protected function initPropertyOrigin(): static
    {
        $this->properties['commandOptions']['origin'] = [
            'type' => 'value:false:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOrigin(array $properties): static
    {
        if (array_key_exists('origin', $properties)) {
            $this->setOrigin($properties['origin']);
        }

        return $this;
    }

    public function getOrigin(): null|false|string
    {
        return $this->properties['commandOptions']['origin']['value'];
    }

    public function setOrigin(null|false|string $value): static
    {
        $this->properties['commandOptions']['origin']['value'] = $value;

        return $this;
    }
}
