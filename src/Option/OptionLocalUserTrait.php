<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionLocalUserTrait
{
    protected function initPropertyLocalUser(): static
    {
        $this->properties['commandOptions']['localUser'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'short' => '-u',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyLocalUser(array $properties): static
    {
        if (array_key_exists('localUser', $properties)) {
            $this->setLocalUser($properties['localUser']);
        }

        return $this;
    }

    public function getLocalUser(): ?string
    {
        return $this->properties['commandOptions']['localUser']['value'];
    }

    public function setLocalUser(?string $value): static
    {
        $this->properties['commandOptions']['localUser']['value'] = $value;

        return $this;
    }
}
