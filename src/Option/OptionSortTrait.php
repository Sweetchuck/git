<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSortTrait
{

    public function initPropertySort(): static
    {
        $this->properties['commandOptions']['sort'] = [
            'type' => 'value:string-multiple',
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySort(array $properties): static
    {
        if (array_key_exists('sort', $properties)) {
            $this->setSort($properties['sort']);
        }

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    public function getSort(): array
    {
        return $this->properties['commandOptions']['sort']['value'];
    }

    /**
     * @param array<string, bool> $value
     *
     */
    public function setSort(array $value): static
    {
        $this->properties['commandOptions']['sort']['value'] = $value;

        return $this;
    }

    /**
     * @param array<string, bool> $value
     */
    public function updateSort(array $value): static
    {
        foreach ($value as $name => $state) {
            $this->properties['commandOptions']['sort']['value'][$name] = $state;
        }

        return $this;
    }
}
