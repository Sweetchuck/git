<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionModifiedTrait
{
    protected function initPropertyModified(): static
    {
        $this->properties['commandOptions']['modified'] = [
            'type' => 'state:true',
            'name' => '--modified',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyModified(array $properties): static
    {
        if (array_key_exists('modified', $properties)) {
            $this->setModified($properties['modified']);
        }

        return $this;
    }

    public function getModified(): ?bool
    {
        return $this->properties['commandOptions']['modified']['state'];
    }

    public function setModified(?bool $value): static
    {
        $this->properties['commandOptions']['modified']['state'] = $value;

        return $this;
    }
}
