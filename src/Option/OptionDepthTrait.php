<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDepthTrait
{
    protected function initPropertyDepth(): static
    {
        $this->properties['commandOptions']['depth'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDepth(array $properties): static
    {
        if (array_key_exists('depth', $properties)) {
            $this->setDepth($properties['depth']);
        }

        return $this;
    }

    public function getDepth(): ?int
    {
        return $this->properties['commandOptions']['depth']['value'];
    }

    public function setDepth(?int $value): static
    {
        $this->properties['commandOptions']['depth']['value'] = $value;

        return $this;
    }
}
