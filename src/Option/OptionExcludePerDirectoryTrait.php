<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExcludePerDirectoryTrait
{
    protected function initPropertyExcludePerDirectory(): static
    {
        $this->properties['commandOptions']['excludePerDirectory'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExcludePerDirectory(array $properties): static
    {
        if (array_key_exists('excludePerDirectory', $properties)) {
            $this->setExcludePerDirectory($properties['excludePerDirectory']);
        }

        return $this;
    }

    public function getExcludePerDirectory(): ?string
    {
        return $this->properties['commandOptions']['excludePerDirectory']['value'];
    }

    public function setExcludePerDirectory(?string $value): static
    {
        $this->properties['commandOptions']['excludePerDirectory']['value'] = $value;

        return $this;
    }
}
