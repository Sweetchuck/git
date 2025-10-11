<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreUnmergedTrait
{
    protected function initPropertyIgnoreUnmerged(): static
    {
        $this->properties['commandOptions']['ignoreUnmerged'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreUnmerged(array $properties): static
    {
        if (array_key_exists('ignoreUnmerged', $properties)) {
            $this->setIgnoreUnmerged($properties['ignoreUnmerged']);
        }

        return $this;
    }

    public function getIgnoreUnmerged(): ?bool
    {
        return $this->properties['commandOptions']['ignoreUnmerged']['state'];
    }

    public function setIgnoreUnmerged(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreUnmerged']['state'] = $value;

        return $this;
    }
}
