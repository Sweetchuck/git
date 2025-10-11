<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAllMatchTrait
{
    protected function initPropertyAllMatch(): static
    {
        $this->properties['commandOptions']['allMatch'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAllMatch(array $properties): static
    {
        if (array_key_exists('allMatch', $properties)) {
            $this->setAllMatch($properties['allMatch']);
        }

        return $this;
    }

    public function getAllMatch(): ?bool
    {
        return $this->properties['commandOptions']['allMatch']['state'];
    }

    public function setAllMatch(?bool $value): static
    {
        $this->properties['commandOptions']['allMatch']['state'] = $value;

        return $this;
    }
}
