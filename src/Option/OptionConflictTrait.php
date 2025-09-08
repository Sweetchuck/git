<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionConflictTrait
{
    protected function initPropertyConflict(): static
    {
        $this->properties['commandOptions']['conflict'] = [
            'type' => 'value:false:string-required',
            'name' => '--conflict',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyConflict(array $properties): static
    {
        if (array_key_exists('conflict', $properties)) {
            $this->setConflict($properties['conflict']);
        }

        return $this;
    }

    public function getConflict(): null|false|string
    {
        return $this->properties['commandOptions']['conflict']['value'];
    }

    public function setConflict(null|false|string $value): static
    {
        $this->properties['commandOptions']['conflict']['value'] = $value;

        return $this;
    }
}
