<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOverwriteIgnoreTrait
{
    protected function initPropertyOverwriteIgnore(): static
    {
        $this->properties['commandOptions']['overwriteIgnore'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOverwriteIgnore(array $properties): static
    {
        if (array_key_exists('overwriteIgnore', $properties)) {
            $this->setOverwriteIgnore($properties['overwriteIgnore']);
        }

        return $this;
    }

    public function getOverwriteIgnore(): ?bool
    {
        return $this->properties['commandOptions']['overwriteIgnore']['state'];
    }

    public function setOverwriteIgnore(?bool $value): static
    {
        $this->properties['commandOptions']['overwriteIgnore']['state'] = $value;

        return $this;
    }
}
