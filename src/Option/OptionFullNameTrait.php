<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFullNameTrait
{
    protected function initPropertyFullName(): static
    {
        $this->properties['commandOptions']['fullName'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFullName(array $properties): static
    {
        if (array_key_exists('fullName', $properties)) {
            $this->setFullName($properties['fullName']);
        }

        return $this;
    }

    public function getFullName(): ?bool
    {
        return $this->properties['commandOptions']['fullName']['state'];
    }

    public function setFullName(?bool $value): static
    {
        $this->properties['commandOptions']['fullName']['state'] = $value;

        return $this;
    }
}
