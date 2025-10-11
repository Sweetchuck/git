<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDirectoryTrait
{
    protected function initPropertyDirectory(): static
    {
        $this->properties['commandOptions']['directory'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDirectory(array $properties): static
    {
        if (array_key_exists('directory', $properties)) {
            $this->setDirectory($properties['directory']);
        }

        return $this;
    }

    public function getDirectory(): ?bool
    {
        return $this->properties['commandOptions']['directory']['state'];
    }

    public function setDirectory(?bool $value): static
    {
        $this->properties['commandOptions']['directory']['state'] = $value;

        return $this;
    }
}
