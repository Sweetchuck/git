<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRecurseSubmodulesTrait
{
    protected function initPropertyRecurseSubmodules(): static
    {
        $this->properties['commandOptions']['recurseSubmodules'] = [
            'type' => CommandOptionType::ValueMultiFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRecurseSubmodules(array $properties): static
    {
        if (array_key_exists('recurseSubmodules', $properties)) {
            $this->setRecurseSubmodules($properties['recurseSubmodules']);
        }

        return $this;
    }

    /**
     * @return null|array<bool|string>
     */
    public function getRecurseSubmodules(): null|array
    {
        return $this->properties['commandOptions']['recurseSubmodules']['value'];
    }

    /**
     * @param null|array<bool|string> $value
     */
    public function setRecurseSubmodules(null|array $value): static
    {
        $this->properties['commandOptions']['recurseSubmodules']['value'] = $value;

        return $this;
    }
}
