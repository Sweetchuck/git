<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionStagedTrait
{
    protected function initPropertyStaged(): static
    {
        $this->properties['commandOptions']['staged'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyStaged(array $properties): static
    {
        if (array_key_exists('staged', $properties)) {
            $this->setStaged($properties['staged']);
        }

        return $this;
    }

    public function getStaged(): ?bool
    {
        return $this->properties['commandOptions']['staged']['state'];
    }

    public function setStaged(?bool $value): static
    {
        $this->properties['commandOptions']['staged']['state'] = $value;

        return $this;
    }
}
