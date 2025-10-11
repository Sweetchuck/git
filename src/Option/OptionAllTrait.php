<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAllTrait
{

    protected function initPropertyAll(): static
    {
        $this->properties['commandOptions']['all'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAll(array $properties): static
    {
        if (array_key_exists('all', $properties)) {
            $this->setAll($properties['all']);
        }

        return $this;
    }

    public function getAll(): ?bool
    {
        return $this->properties['commandOptions']['all']['state'];
    }

    public function setAll(?bool $value): static
    {
        $this->properties['commandOptions']['all']['state'] = $value;

        return $this;
    }
}
