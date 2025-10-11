<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionErrorUnmatchTrait
{
    protected function initPropertyErrorUnmatch(): static
    {
        $this->properties['commandOptions']['errorUnmatch'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyErrorUnmatch(array $properties): static
    {
        if (array_key_exists('errorUnmatch', $properties)) {
            $this->setErrorUnmatch($properties['errorUnmatch']);
        }

        return $this;
    }

    public function getErrorUnmatch(): ?bool
    {
        return $this->properties['commandOptions']['errorUnmatch']['state'];
    }

    public function setErrorUnmatch(?bool $value): static
    {
        $this->properties['commandOptions']['errorUnmatch']['state'] = $value;

        return $this;
    }
}
