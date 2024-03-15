<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionGuessTrait
{
    protected function initPropertyGuess(): static
    {
        $this->properties['commandOptions']['guess'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyGuess(array $properties): static
    {
        if (array_key_exists('guess', $properties)) {
            $this->setGuess($properties['guess']);
        }

        return $this;
    }

    public function getGuess(): ?bool
    {
        return $this->properties['commandOptions']['guess']['state'];
    }

    public function setGuess(?bool $value): static
    {
        $this->properties['commandOptions']['guess']['state'] = $value;

        return $this;
    }
}
