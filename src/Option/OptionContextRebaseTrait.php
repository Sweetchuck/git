<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionContextRebaseTrait
{
    protected function initPropertyContext(): static
    {
        $this->properties['commandOptions']['context'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'name' => '-C',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyContext(array $properties): static
    {
        if (array_key_exists('context', $properties)) {
            $this->setContext($properties['context']);
        }

        return $this;
    }

    public function getContext(): ?int
    {
        return $this->properties['commandOptions']['context']['value'];
    }

    public function setContext(null|int $value): static
    {
        $this->properties['commandOptions']['context']['value'] = $value;

        return $this;
    }
}
