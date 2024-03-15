<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDefaultTrait
{
    protected function initPropertyDefault(): static
    {
        $this->properties['commandOptions']['default'] = [
            'type' => 'value:true-false:string',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDefault(array $properties): static
    {
        if (array_key_exists('default', $properties)) {
            $this->setDefault($properties['default']);
        }

        return $this;
    }

    public function getDefault(): null|bool|string
    {
        return $this->properties['commandOptions']['default']['value'];
    }

    public function setDefault(null|bool|string $value): static
    {
        $this->properties['commandOptions']['default']['value'] = $value;

        return $this;
    }
}
