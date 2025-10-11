<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOnlyMatchingTrait
{
    protected function initPropertyOnlyMatching(): static
    {
        $this->properties['commandOptions']['onlyMatching'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOnlyMatching(array $properties): static
    {
        if (array_key_exists('onlyMatching', $properties)) {
            $this->setOnlyMatching($properties['onlyMatching']);
        }

        return $this;
    }

    public function getOnlyMatching(): ?bool
    {
        return $this->properties['commandOptions']['onlyMatching']['state'];
    }

    public function setOnlyMatching(?bool $value): static
    {
        $this->properties['commandOptions']['onlyMatching']['state'] = $value;

        return $this;
    }
}
