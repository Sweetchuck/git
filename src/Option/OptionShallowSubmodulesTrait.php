<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionShallowSubmodulesTrait
{
    protected function initPropertyShallowSubmodules(): static
    {
        $this->properties['commandOptions']['shallowSubmodules'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShallowSubmodules(array $properties): static
    {
        if (array_key_exists('shallowSubmodules', $properties)) {
            $this->setShallowSubmodules($properties['shallowSubmodules']);
        }

        return $this;
    }

    public function getShallowSubmodules(): ?bool
    {
        return $this->properties['commandOptions']['shallowSubmodules']['state'];
    }

    public function setShallowSubmodules(?bool $value): static
    {
        $this->properties['commandOptions']['shallowSubmodules']['state'] = $value;

        return $this;
    }
}
