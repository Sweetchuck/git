<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreErrorsTrait
{
    protected function initPropertyIgnoreErrors(): static
    {
        $this->properties['commandOptions']['ignoreErrors'] = [
            'type' => 'state:true',
            'name' => '--ignore-errors',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreErrors(array $properties): static
    {
        if (array_key_exists('ignoreErrors', $properties)) {
            $this->setIgnoreErrors($properties['ignoreErrors']);
        }

        return $this;
    }

    public function getIgnoreErrors(): ?bool
    {
        return $this->properties['commandOptions']['ignoreErrors']['state'];
    }

    public function setIgnoreErrors(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreErrors']['state'] = $value;

        return $this;
    }
}
