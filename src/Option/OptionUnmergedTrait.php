<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUnmergedTrait
{
    protected function initPropertyUnmerged(): static
    {
        $this->properties['commandOptions']['unmerged'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUnmerged(array $properties): static
    {
        if (array_key_exists('unmerged', $properties)) {
            $this->setUnmerged($properties['unmerged']);
        }

        return $this;
    }

    public function getUnmerged(): ?bool
    {
        return $this->properties['commandOptions']['unmerged']['state'];
    }

    public function setUnmerged(?bool $value): static
    {
        $this->properties['commandOptions']['unmerged']['state'] = $value;

        return $this;
    }
}
