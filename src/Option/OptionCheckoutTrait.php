<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionCheckoutTrait
{
    protected function initPropertyCheckout(): static
    {
        $this->properties['commandOptions']['checkout'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyCheckout(array $properties): static
    {
        if (array_key_exists('checkout', $properties)) {
            $this->setCheckout($properties['checkout']);
        }

        return $this;
    }

    public function getCheckout(): ?bool
    {
        return $this->properties['commandOptions']['checkout']['state'];
    }

    public function setCheckout(?bool $value): static
    {
        $this->properties['commandOptions']['checkout']['state'] = $value;

        return $this;
    }
}
