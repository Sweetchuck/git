<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNoEmptyDirectoryTrait
{
    protected function initPropertyNoEmptyDirectory(): static
    {
        $this->properties['commandOptions']['noEmptyDirectory'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNoEmptyDirectory(array $properties): static
    {
        if (array_key_exists('noEmptyDirectory', $properties)) {
            $this->setNoEmptyDirectory($properties['noEmptyDirectory']);
        }

        return $this;
    }

    public function getNoEmptyDirectory(): ?bool
    {
        return $this->properties['commandOptions']['noEmptyDirectory']['state'];
    }

    public function setNoEmptyDirectory(?bool $value): static
    {
        $this->properties['commandOptions']['noEmptyDirectory']['state'] = $value;

        return $this;
    }
}
