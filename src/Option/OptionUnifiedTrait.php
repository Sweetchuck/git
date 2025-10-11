<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUnifiedTrait
{

    protected function initPropertyUnified(): static
    {
        $this->properties['commandOptions']['unified'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUnified(array $properties): static
    {
        if (array_key_exists('unified', $properties)) {
            $this->setUnified($properties['unified']);
        }

        return $this;
    }

    public function getUnified(): ?int
    {
        return $this->properties['commandOptions']['unified']['value'];
    }

    public function setUnified(?int $value): static
    {
        $this->properties['commandOptions']['unified']['value'] = $value;

        return $this;
    }
}
