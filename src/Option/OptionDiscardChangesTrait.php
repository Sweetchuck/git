<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDiscardChangesTrait
{
    protected function initPropertyDiscardChanges(): static
    {
        $this->properties['commandOptions']['discardChanges'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDiscardChanges(array $properties): static
    {
        if (array_key_exists('discardChanges', $properties)) {
            $this->setDiscardChanges($properties['discardChanges']);
        }

        return $this;
    }

    public function getDiscardChanges(): ?bool
    {
        return $this->properties['commandOptions']['discardChanges']['state'];
    }

    public function setDiscardChanges(?bool $value): static
    {
        $this->properties['commandOptions']['discardChanges']['state'] = $value;

        return $this;
    }
}
