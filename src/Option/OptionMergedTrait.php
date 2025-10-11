<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMergedTrait
{

    protected function initPropertyMerged(): static
    {
        $this->properties['commandOptions']['merged'] = [
            'type' => CommandOptionType::StateStringRequiredMulti,
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMerged(array $properties): static
    {
        if (array_key_exists('merged', $properties)) {
            $this->setMerged($properties['merged']);
        }

        return $this;
    }

    public function getMerged(): ?bool
    {
        return $this->properties['commandOptions']['merged']['value'];
    }

    /**
     * @param array<string, bool> $value
     */
    public function setMerged(array $value): static
    {
        $this->properties['commandOptions']['merged']['value'] = $value;

        return $this;
    }
}
