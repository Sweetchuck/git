<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRemotesTrait
{

    protected function initPropertyRemotes(): static
    {
        $this->properties['commandOptions']['remotes'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRemotes(array $properties): static
    {
        if (array_key_exists('remotes', $properties)) {
            $this->setRemotes($properties['remotes']);
        }

        return $this;
    }

    public function getRemotes(): ?bool
    {
        return $this->properties['commandOptions']['remotes']['state'];
    }

    public function setRemotes(?bool $value): static
    {
        $this->properties['commandOptions']['remotes']['state'] = $value;

        return $this;
    }
}
