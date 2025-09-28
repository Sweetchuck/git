<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDeepenTrait
{

    protected function initPropertyDeepen(): static
    {
        $this->properties['commandOptions']['deepen'] = [
            'type' => 'value:false:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDeepen(array $properties): static
    {
        if (array_key_exists('deepen', $properties)) {
            $this->setDeepen($properties['deepen']);
        }

        return $this;
    }

    public function getDeepen(): null|false|int
    {
        return $this->properties['commandOptions']['deepen']['value'];
    }

    public function setDeepen(null|false|int $value): static
    {
        $this->properties['commandOptions']['deepen']['value'] = $value;

        return $this;
    }
}
