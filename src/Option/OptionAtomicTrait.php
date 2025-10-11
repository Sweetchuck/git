<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAtomicTrait
{

    protected function initPropertyAtomic(): static
    {
        $this->properties['commandOptions']['atomic'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAtomic(array $properties): static
    {
        if (array_key_exists('atomic', $properties)) {
            $this->setAtomic($properties['atomic']);
        }

        return $this;
    }

    public function getAtomic(): ?bool
    {
        return $this->properties['commandOptions']['atomic']['state'];
    }

    public function setAtomic(?bool $value): static
    {
        $this->properties['commandOptions']['atomic']['state'] = $value;

        return $this;
    }
}
