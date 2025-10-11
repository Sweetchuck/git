<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionKeepTrait
{

    protected function initPropertyKeep(): static
    {
        $this->properties['commandOptions']['keep'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyKeep(array $properties): static
    {
        if (array_key_exists('keep', $properties)) {
            $this->setKeep($properties['keep']);
        }

        return $this;
    }

    public function getKeep(): ?bool
    {
        return $this->properties['commandOptions']['keep']['state'];
    }

    public function setKeep(?bool $value): static
    {
        $this->properties['commandOptions']['keep']['state'] = $value;

        return $this;
    }
}
