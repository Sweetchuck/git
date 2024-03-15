<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFilterTrait
{
    protected function initPropertyFilter(): static
    {
        $this->properties['commandOptions']['filter'] = [
            'type' => 'value:multi:false-string',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFilter(array $properties): static
    {
        if (array_key_exists('filter', $properties)) {
            $this->setFilter($properties['filter']);
        }

        return $this;
    }

    /**
     * @return null|array<false|string>
     */
    public function getFilter(): null|array
    {
        return $this->properties['commandOptions']['filter']['value'];
    }

    /**
     * @param null|array<false|string> $value
     */
    public function setFilter(null|array $value): static
    {
        $this->properties['commandOptions']['filter']['value'] = $value;

        return $this;
    }
}
