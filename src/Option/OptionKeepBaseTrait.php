<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionKeepBaseTrait
{

    protected function initPropertyKeepBase(): static
    {
        $this->properties['commandOptions']['keepBase'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyKeepBase(array $properties): static
    {
        if (array_key_exists('keepBase', $properties)) {
            $this->setKeepBase($properties['keepBase']);
        }

        return $this;
    }

    public function getKeepBase(): ?bool
    {
        return $this->properties['commandOptions']['keepBase']['state'];
    }

    public function setKeepBase(?bool $value): static
    {
        $this->properties['commandOptions']['keepBase']['state'] = $value;

        return $this;
    }
}
