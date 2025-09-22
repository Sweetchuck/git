<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIntoNameTrait
{
    protected function initPropertyIntoName(): static
    {
        $this->properties['commandOptions']['intoName'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIntoName(array $properties): static
    {
        if (array_key_exists('intoName', $properties)) {
            $this->setIntoName($properties['intoName']);
        }

        return $this;
    }

    public function getIntoName(): null|false|string
    {
        return $this->properties['commandOptions']['intoName']['value'];
    }

    public function setIntoName(null|false|string $value): static
    {
        $this->properties['commandOptions']['intoName']['value'] = $value;

        return $this;
    }
}
