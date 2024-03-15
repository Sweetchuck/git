<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIntentToAddTrait
{
    protected function initPropertyIntentToAdd(): static
    {
        $this->properties['commandOptions']['intentToAdd'] = [
            'type' => 'state:true',
            'name' => '--intent-to-add',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIntentToAdd(array $properties): static
    {
        if (array_key_exists('intentToAdd', $properties)) {
            $this->setIntentToAdd($properties['intentToAdd']);
        }

        return $this;
    }

    public function getIntentToAdd(): ?bool
    {
        return $this->properties['commandOptions']['intentToAdd']['state'];
    }

    public function setIntentToAdd(?bool $value): static
    {
        $this->properties['commandOptions']['intentToAdd']['state'] = $value;

        return $this;
    }
}
