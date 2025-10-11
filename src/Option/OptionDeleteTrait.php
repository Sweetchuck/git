<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDeleteTrait
{
    protected function initPropertyDelete(): static
    {
        $this->properties['commandOptions']['delete'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDelete(array $properties): static
    {
        if (array_key_exists('delete', $properties)) {
            $this->setDelete($properties['delete']);
        }

        return $this;
    }

    public function getDelete(): ?bool
    {
        return $this->properties['commandOptions']['delete']['state'];
    }

    public function setDelete(?bool $value): static
    {
        $this->properties['commandOptions']['delete']['state'] = $value;

        return $this;
    }
}
