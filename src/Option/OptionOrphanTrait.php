<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOrphanTrait
{
    protected function initPropertyOrphan(): static
    {
        $this->properties['commandOptions']['orphan'] = [
            'type' => 'state:true',
            'name' => '--orphan',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOrphan(array $properties): static
    {
        if (array_key_exists('orphan', $properties)) {
            $this->setOrphan($properties['orphan']);
        }

        return $this;
    }

    public function getOrphan(): ?bool
    {
        return $this->properties['commandOptions']['orphan']['state'];
    }

    public function setOrphan(?bool $value): static
    {
        $this->properties['commandOptions']['orphan']['state'] = $value;

        return $this;
    }
}
