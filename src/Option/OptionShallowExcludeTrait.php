<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionShallowExcludeTrait
{
    protected function initPropertyShallowExclude(): static
    {
        $this->properties['commandOptions']['shallowExclude'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShallowExclude(array $properties): static
    {
        if (array_key_exists('shallowExclude', $properties)) {
            $this->setShallowExclude($properties['shallowExclude']);
        }

        return $this;
    }

    public function getShallowExclude(): null|false|string
    {
        return $this->properties['commandOptions']['shallowExclude']['value'];
    }

    public function setShallowExclude(null|false|string $value): static
    {
        $this->properties['commandOptions']['shallowExclude']['value'] = $value;

        return $this;
    }
}
