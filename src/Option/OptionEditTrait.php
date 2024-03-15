<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionEditTrait
{
    protected function initPropertyEdit(): static
    {
        $this->properties['commandOptions']['edit'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyEdit(array $properties): static
    {
        if (array_key_exists('edit', $properties)) {
            $this->setEdit($properties['edit']);
        }

        return $this;
    }

    public function getEdit(): ?bool
    {
        return $this->properties['commandOptions']['edit']['state'];
    }

    public function setEdit(?bool $value): static
    {
        $this->properties['commandOptions']['edit']['state'] = $value;

        return $this;
    }
}
