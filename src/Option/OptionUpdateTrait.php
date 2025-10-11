<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUpdateTrait
{
    protected function initPropertyUpdate(): static
    {
        $this->properties['commandOptions']['update'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUpdate(array $properties): static
    {
        if (array_key_exists('update', $properties)) {
            $this->setUpdate($properties['update']);
        }

        return $this;
    }

    public function getUpdate(): ?bool
    {
        return $this->properties['commandOptions']['update']['state'];
    }

    public function setUpdate(?bool $value): static
    {
        $this->properties['commandOptions']['update']['state'] = $value;

        return $this;
    }
}
