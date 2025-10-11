<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionForceTrait
{
    protected function initPropertyForce(): static
    {
        $this->properties['commandOptions']['force'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyForce(array $properties): static
    {
        if (array_key_exists('force', $properties)) {
            $this->setForce($properties['force']);
        }

        return $this;
    }

    public function getForce(): ?bool
    {
        return $this->properties['commandOptions']['force']['state'];
    }

    public function setForce(?bool $value): static
    {
        $this->properties['commandOptions']['force']['state'] = $value;

        return $this;
    }
}
