<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreRemovalTrait
{
    protected function initPropertyIgnoreRemoval(): static
    {
        $this->properties['commandOptions']['ignoreRemoval'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreRemoval(array $properties): static
    {
        if (array_key_exists('ignoreRemoval', $properties)) {
            $this->setIgnoreRemoval($properties['ignoreRemoval']);
        }

        return $this;
    }

    public function getIgnoreRemoval(): ?bool
    {
        return $this->properties['commandOptions']['ignoreRemoval']['state'];
    }

    public function setIgnoreRemoval(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreRemoval']['state'] = $value;

        return $this;
    }
}
