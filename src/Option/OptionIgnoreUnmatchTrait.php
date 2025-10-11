<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreUnmatchTrait
{
    protected function initPropertyIgnoreUnmatch(): static
    {
        $this->properties['commandOptions']['ignoreUnmatch'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreUnmatch(array $properties): static
    {
        if (array_key_exists('ignoreUnmatch', $properties)) {
            $this->setIgnoreUnmatch($properties['ignoreUnmatch']);
        }

        return $this;
    }

    public function getIgnoreUnmatch(): ?bool
    {
        return $this->properties['commandOptions']['ignoreUnmatch']['state'];
    }

    public function setIgnoreUnmatch(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreUnmatch']['state'] = $value;

        return $this;
    }
}
