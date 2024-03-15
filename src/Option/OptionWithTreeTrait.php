<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionWithTreeTrait
{
    protected function initPropertyWithTree(): static
    {
        $this->properties['commandOptions']['withTree'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyWithTree(array $properties): static
    {
        if (array_key_exists('withTree', $properties)) {
            $this->setWithTree($properties['withTree']);
        }

        return $this;
    }

    public function getWithTree(): ?string
    {
        return $this->properties['commandOptions']['withTree']['value'];
    }

    public function setWithTree(?string $value): static
    {
        $this->properties['commandOptions']['withTree']['value'] = $value;

        return $this;
    }
}
