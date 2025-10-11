<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExcludeStandardTrait
{
    protected function initPropertyExcludeStandard(): static
    {
        $this->properties['commandOptions']['excludeStandard'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExcludeStandard(array $properties): static
    {
        if (array_key_exists('excludeStandard', $properties)) {
            $this->setExcludeStandard($properties['excludeStandard']);
        }

        return $this;
    }

    public function getExcludeStandard(): ?bool
    {
        return $this->properties['commandOptions']['excludeStandard']['state'];
    }

    public function setExcludeStandard(?bool $value): static
    {
        $this->properties['commandOptions']['excludeStandard']['state'] = $value;

        return $this;
    }
}
