<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPatchTrait
{
    protected function initPropertyPatch(): static
    {
        $this->properties['commandOptions']['patch'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPatch(array $properties): static
    {
        if (array_key_exists('patch', $properties)) {
            $this->setPatch($properties['patch']);
        }

        return $this;
    }

    public function getPatch(): ?bool
    {
        return $this->properties['commandOptions']['patch']['state'];
    }

    public function setPatch(?bool $value): static
    {
        $this->properties['commandOptions']['patch']['state'] = $value;

        return $this;
    }
}
