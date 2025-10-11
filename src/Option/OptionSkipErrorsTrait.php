<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSkipErrorsTrait
{
    protected function initPropertySkipErrors(): static
    {
        $this->properties['commandOptions']['skipErrors'] = [
            'type' => CommandOptionType::StateBool,
            'name' => '-k',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySkipErrors(array $properties): static
    {
        if (array_key_exists('skipErrors', $properties)) {
            $this->setSkipErrors($properties['skipErrors']);
        }

        return $this;
    }

    public function getSkipErrors(): ?bool
    {
        return $this->properties['commandOptions']['skipErrors']['state'];
    }

    public function setSkipErrors(?bool $value): static
    {
        $this->properties['commandOptions']['skipErrors']['state'] = $value;

        return $this;
    }
}
