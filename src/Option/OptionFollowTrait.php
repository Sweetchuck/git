<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFollowTrait
{
    protected function initPropertyFollow(): static
    {
        $this->properties['commandOptions']['follow'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFollow(array $properties): static
    {
        if (array_key_exists('follow', $properties)) {
            $this->setFollow($properties['follow']);
        }

        return $this;
    }

    public function getFollow(): ?bool
    {
        return $this->properties['commandOptions']['follow']['state'];
    }

    public function setFollow(?bool $value): static
    {
        $this->properties['commandOptions']['follow']['state'] = $value;

        return $this;
    }
}
