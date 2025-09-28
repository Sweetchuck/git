<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAppendTrait
{

    protected function initPropertyAppend(): static
    {
        $this->properties['commandOptions']['append'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAppend(array $properties): static
    {
        if (array_key_exists('append', $properties)) {
            $this->setAppend($properties['append']);
        }

        return $this;
    }

    public function getAppend(): ?bool
    {
        return $this->properties['commandOptions']['append']['state'];
    }

    public function setAppend(?bool $value): static
    {
        $this->properties['commandOptions']['append']['state'] = $value;

        return $this;
    }
}
