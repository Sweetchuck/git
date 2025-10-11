<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMergeTrait
{
    protected function initPropertyMerge(): static
    {
        $this->properties['commandOptions']['merge'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMerge(array $properties): static
    {
        if (array_key_exists('merge', $properties)) {
            $this->setMerge($properties['merge']);
        }

        return $this;
    }

    public function getMerge(): ?bool
    {
        return $this->properties['commandOptions']['merge']['state'];
    }

    public function setMerge(?bool $value): static
    {
        $this->properties['commandOptions']['merge']['state'] = $value;

        return $this;
    }
}
