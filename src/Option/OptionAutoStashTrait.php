<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAutoStashTrait
{
    protected function initPropertyAutoStash(): static
    {
        $this->properties['commandOptions']['autoStash'] = [
            'type' => CommandOptionType::StateBool,
            'name' => '--autostash',
            'name-no' => '--no-autostash',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAutoStash(array $properties): static
    {
        if (array_key_exists('autoStash', $properties)) {
            $this->setAutoStash($properties['autoStash']);
        }

        return $this;
    }

    public function getAutoStash(): ?bool
    {
        return $this->properties['commandOptions']['autoStash']['state'];
    }

    public function setAutoStash(?bool $value): static
    {
        $this->properties['commandOptions']['autoStash']['state'] = $value;

        return $this;
    }
}
