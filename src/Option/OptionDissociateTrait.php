<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDissociateTrait
{
    protected function initPropertyDissociate(): static
    {
        $this->properties['commandOptions']['dissociate'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDissociate(array $properties): static
    {
        if (array_key_exists('dissociate', $properties)) {
            $this->setDissociate($properties['dissociate']);
        }

        return $this;
    }

    public function getDissociate(): ?bool
    {
        return $this->properties['commandOptions']['dissociate']['state'];
    }

    public function setDissociate(?bool $value): static
    {
        $this->properties['commandOptions']['dissociate']['state'] = $value;

        return $this;
    }
}
