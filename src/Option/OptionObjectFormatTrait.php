<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionObjectFormatTrait
{
    protected function initPropertyObjectFormat(): static
    {
        $this->properties['commandOptions']['objectFormat'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyObjectFormat(array $properties): static
    {
        if (array_key_exists('objectFormat', $properties)) {
            $this->setObjectFormat($properties['objectFormat']);
        }

        return $this;
    }

    public function getObjectFormat(): ?string
    {
        return $this->properties['commandOptions']['objectFormat']['value'];
    }

    public function setObjectFormat(?string $value): static
    {
        $this->properties['commandOptions']['objectFormat']['value'] = $value;

        return $this;
    }
}
