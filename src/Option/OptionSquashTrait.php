<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSquashTrait
{
    protected function initPropertySquash(): static
    {
        $this->properties['commandOptions']['squash'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySquash(array $properties): static
    {
        if (array_key_exists('squash', $properties)) {
            $this->setSquash($properties['squash']);
        }

        return $this;
    }

    public function getSquash(): ?bool
    {
        return $this->properties['commandOptions']['squash']['state'];
    }

    public function setSquash(?bool $value): static
    {
        $this->properties['commandOptions']['squash']['state'] = $value;

        return $this;
    }
}
