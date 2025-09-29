<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOnToTrait
{
    protected function initPropertyOnTo(): static
    {
        $this->properties['commandOptions']['onTo'] = [
            'type' => 'value:string-required',
            'name' => '--onto',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOnTo(array $properties): static
    {
        if (array_key_exists('onTo', $properties)) {
            $this->setOnTo($properties['onTo']);
        }

        return $this;
    }

    public function getOnTo(): null|string
    {
        return $this->properties['commandOptions']['onTo']['value'];
    }

    /**
     * @todo Use \Sweetchuck\Git\OnTo enum.
     */
    public function setOnTo(null|string $value): static
    {
        $this->properties['commandOptions']['onTo']['value'] = $value;

        return $this;
    }
}
