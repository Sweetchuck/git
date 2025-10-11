<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAmendTrait
{
    protected function initPropertyAmend(): static
    {
        $this->properties['commandOptions']['amend'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAmend(array $properties): static
    {
        if (array_key_exists('amend', $properties)) {
            $this->setAmend($properties['amend']);
        }

        return $this;
    }

    public function getAmend(): ?bool
    {
        return $this->properties['commandOptions']['amend']['state'];
    }

    public function setAmend(?bool $value): static
    {
        $this->properties['commandOptions']['amend']['state'] = $value;

        return $this;
    }
}
