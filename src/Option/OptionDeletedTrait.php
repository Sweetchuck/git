<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDeletedTrait
{
    protected function initPropertyDeleted(): static
    {
        $this->properties['commandOptions']['deleted'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDeleted(array $properties): static
    {
        if (array_key_exists('deleted', $properties)) {
            $this->setDeleted($properties['deleted']);
        }

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->properties['commandOptions']['deleted']['state'];
    }

    public function setDeleted(?bool $value): static
    {
        $this->properties['commandOptions']['deleted']['state'] = $value;

        return $this;
    }
}
