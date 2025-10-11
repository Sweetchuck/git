<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreMissingTrait
{
    protected function initPropertyIgnoreMissing(): static
    {
        $this->properties['commandOptions']['ignoreMissing'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreMissing(array $properties): static
    {
        if (array_key_exists('ignoreMissing', $properties)) {
            $this->setIgnoreMissing($properties['ignoreMissing']);
        }

        return $this;
    }

    public function getIgnoreMissing(): ?bool
    {
        return $this->properties['commandOptions']['ignoreMissing']['state'];
    }

    public function setIgnoreMissing(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreMissing']['state'] = $value;

        return $this;
    }
}
