<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionShallowSinceTrait
{
    protected function initPropertyShallowSince(): static
    {
        $this->properties['commandOptions']['shallowSince'] = [
            'type' => 'value:false:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShallowSince(array $properties): static
    {
        if (array_key_exists('shallowSince', $properties)) {
            $this->setShallowSince($properties['shallowSince']);
        }

        return $this;
    }

    public function getShallowSince(): null|false|string
    {
        return $this->properties['commandOptions']['shallowSince']['value'];
    }

    public function setShallowSince(null|false|string $value): static
    {
        $this->properties['commandOptions']['shallowSince']['value'] = $value;

        return $this;
    }
}
