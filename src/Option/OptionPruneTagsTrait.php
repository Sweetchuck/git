<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPruneTagsTrait
{

    protected function initPropertyPruneTags(): static
    {
        $this->properties['commandOptions']['pruneTags'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPruneTags(array $properties): static
    {
        if (array_key_exists('pruneTags', $properties)) {
            $this->setPruneTags($properties['pruneTags']);
        }

        return $this;
    }

    public function getPruneTags(): ?bool
    {
        return $this->properties['commandOptions']['pruneTags']['state'];
    }

    public function setPruneTags(?bool $value): static
    {
        $this->properties['commandOptions']['pruneTags']['state'] = $value;

        return $this;
    }
}
