<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRenormalizeTrait
{
    protected function initPropertyRenormalize(): static
    {
        $this->properties['commandOptions']['renormalize'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRenormalize(array $properties): static
    {
        if (array_key_exists('renormalize', $properties)) {
            $this->setRenormalize($properties['renormalize']);
        }

        return $this;
    }

    public function getRenormalize(): ?bool
    {
        return $this->properties['commandOptions']['renormalize']['state'];
    }

    public function setRenormalize(?bool $value): static
    {
        $this->properties['commandOptions']['renormalize']['state'] = $value;

        return $this;
    }
}
