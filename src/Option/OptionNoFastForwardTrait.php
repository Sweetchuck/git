<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNoFastForwardTrait
{
    protected function initPropertyNoFastForward(): static
    {
        $this->properties['commandOptions']['noFastForward'] = [
            'type' => CommandOptionType::StateTrue,
            'name' => '--no-ff',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNoFastForward(array $properties): static
    {
        if (array_key_exists('noFastForward', $properties)) {
            $this->setNoFastForward($properties['noFastForward']);
        }

        return $this;
    }

    public function getNoFastForward(): ?bool
    {
        return $this->properties['commandOptions']['noFastForward']['state'];
    }

    public function setNoFastForward(?bool $value): static
    {
        $this->properties['commandOptions']['noFastForward']['state'] = $value;

        return $this;
    }
}
