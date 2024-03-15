<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAnnotateTrait
{
    protected function initPropertyAnnotate(): static
    {
        $this->properties['commandOptions']['annotate'] = [
            'type' => 'state:true',
            'short' => '-a',
            'name' => '--annotate',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAnnotate(array $properties): static
    {
        if (array_key_exists('annotate', $properties)) {
            $this->setAnnotate($properties['annotate']);
        }

        return $this;
    }

    public function getAnnotate(): ?bool
    {
        return $this->properties['commandOptions']['annotate']['state'];
    }

    public function setAnnotate(?bool $value): static
    {
        $this->properties['commandOptions']['annotate']['state'] = $value;

        return $this;
    }
}
