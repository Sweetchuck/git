<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionLocalTrait
{
    protected function initPropertyLocal(): static
    {
        $this->properties['commandOptions']['local'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyLocal(array $properties): static
    {
        if (array_key_exists('local', $properties)) {
            $this->setLocal($properties['local']);
        }

        return $this;
    }

    public function getLocal(): ?bool
    {
        return $this->properties['commandOptions']['local']['state'];
    }

    public function setLocal(?bool $value): static
    {
        $this->properties['commandOptions']['local']['state'] = $value;

        return $this;
    }
}
