<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSubmodulePrefixTrait
{
    protected function initPropertySubmodulePrefix(): static
    {
        $this->properties['commandOptions']['submodulePrefix'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySubmodulePrefix(array $properties): static
    {
        if (array_key_exists('submodulePrefix', $properties)) {
            $this->setSubmodulePrefix($properties['submodulePrefix']);
        }

        return $this;
    }

    public function getSubmodulePrefix(): null|false|string
    {
        return $this->properties['commandOptions']['submodulePrefix']['value'];
    }

    public function setSubmodulePrefix(null|false|string $value): static
    {
        $this->properties['commandOptions']['submodulePrefix']['value'] = $value;

        return $this;
    }
}
