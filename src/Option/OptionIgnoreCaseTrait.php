<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreCaseTrait
{

    protected function initPropertyIgnoreCase(): static
    {
        $this->properties['commandOptions']['ignoreCase'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreCase(array $properties): static
    {
        if (array_key_exists('ignoreCase', $properties)) {
            $this->setIgnoreCase($properties['ignoreCase']);
        }

        return $this;
    }

    public function getIgnoreCase(): ?bool
    {
        return $this->properties['commandOptions']['ignoreCase']['state'];
    }

    public function setIgnoreCase(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreCase']['state'] = $value;

        return $this;
    }
}
