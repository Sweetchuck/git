<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUpdateShallowTrait
{

    protected function initPropertyUpdateShallow(): static
    {
        $this->properties['commandOptions']['updateShallow'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUpdateShallow(array $properties): static
    {
        if (array_key_exists('updateShallow', $properties)) {
            $this->setUpdateShallow($properties['updateShallow']);
        }

        return $this;
    }

    public function getUpdateShallow(): ?bool
    {
        return $this->properties['commandOptions']['updateShallow']['state'];
    }

    public function setUpdateShallow(?bool $value): static
    {
        $this->properties['commandOptions']['updateShallow']['state'] = $value;

        return $this;
    }
}
