<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRejectShallowTrait
{
    protected function initPropertyRejectShallow(): static
    {
        $this->properties['commandOptions']['rejectShallow'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRejectShallow(array $properties): static
    {
        if (array_key_exists('rejectShallow', $properties)) {
            $this->setRejectShallow($properties['rejectShallow']);
        }

        return $this;
    }

    public function getRejectShallow(): ?bool
    {
        return $this->properties['commandOptions']['rejectShallow']['state'];
    }

    public function setRejectShallow(?bool $value): static
    {
        $this->properties['commandOptions']['rejectShallow']['state'] = $value;

        return $this;
    }
}
