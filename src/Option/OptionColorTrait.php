<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionColorTrait
{
    protected function initPropertyColor(): static
    {
        $this->properties['commandOptions']['color'] = [
            'type' => 'value:true-false:string',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyColor(array $properties): static
    {
        if (array_key_exists('color', $properties)) {
            $this->setColor($properties['color']);
        }

        return $this;
    }

    public function getColor(): null|bool|string
    {
        return $this->properties['commandOptions']['color']['value'];
    }

    public function setColor(null|bool|string $value): static
    {
        $this->properties['commandOptions']['color']['value'] = $value;

        return $this;
    }
}
