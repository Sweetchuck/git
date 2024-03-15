<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionResolveUndoTrait
{
    protected function initPropertyResolveUndo(): static
    {
        $this->properties['commandOptions']['resolveUndo'] = [
            'type' => 'state:true',
            'name' => '--resolve-undo',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyResolveUndo(array $properties): static
    {
        if (array_key_exists('resolveUndo', $properties)) {
            $this->setResolveUndo($properties['resolveUndo']);
        }

        return $this;
    }

    public function getResolveUndo(): ?bool
    {
        return $this->properties['commandOptions']['resolveUndo']['state'];
    }

    public function setResolveUndo(?bool $value): static
    {
        $this->properties['commandOptions']['resolveUndo']['state'] = $value;

        return $this;
    }
}
