<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPathSpecFileNulTrait
{
    protected function initPropertyPathSpecFileNul(): static
    {
        $this->properties['commandOptions']['pathSpecFileNul'] = [
            'type' => 'state:true',
            'name' => '--pathspec-file-nul',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPathSpecFileNul(array $properties): static
    {
        if (array_key_exists('pathSpecFileNul', $properties)) {
            $this->setPathSpecFileNul($properties['pathSpecFileNul']);
        }

        return $this;
    }

    public function getPathSpecFileNul(): ?bool
    {
        return $this->properties['commandOptions']['pathSpecFileNul']['state'];
    }

    public function setPathSpecFileNul(?bool $value): static
    {
        $this->properties['commandOptions']['pathSpecFileNul']['state'] = $value;

        return $this;
    }
}
