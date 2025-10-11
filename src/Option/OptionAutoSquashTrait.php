<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAutoSquashTrait
{

    protected function initPropertyAutoSquash(): static
    {
        $this->properties['commandOptions']['autoSquash'] = [
            'type' => CommandOptionType::StateBool,
            'name' => '--autosquash',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAutoSquash(array $properties): static
    {
        if (array_key_exists('autoSquash', $properties)) {
            $this->setAutoSquash($properties['autoSquash']);
        }

        return $this;
    }

    public function getAutoSquash(): ?bool
    {
        return $this->properties['commandOptions']['autoSquash']['state'];
    }

    public function setAutoSquash(?bool $value): static
    {
        $this->properties['commandOptions']['autoSquash']['state'] = $value;

        return $this;
    }
}
