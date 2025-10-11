<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionReceivePackTrait
{
    protected function initPropertyReceivePack(): static
    {
        $this->properties['commandOptions']['receivePack'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyReceivePack(array $properties): static
    {
        if (array_key_exists('receivePack', $properties)) {
            $this->setReceivePack($properties['receivePack']);
        }

        return $this;
    }

    public function getReceivePack(): null|false|string
    {
        return $this->properties['commandOptions']['receivePack']['value'];
    }

    public function setReceivePack(null|false|string $value): static
    {
        $this->properties['commandOptions']['receivePack']['value'] = $value;

        return $this;
    }
}
