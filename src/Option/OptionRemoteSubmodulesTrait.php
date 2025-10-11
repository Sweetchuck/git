<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRemoteSubmodulesTrait
{

    protected function initPropertyRemoteSubmodules(): static
    {
        $this->properties['commandOptions']['remoteSubmodules'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRemoteSubmodules(array $properties): static
    {
        if (array_key_exists('remoteSubmodules', $properties)) {
            $this->setRemoteSubmodules($properties['remoteSubmodules']);
        }

        return $this;
    }

    public function getRemoteSubmodules(): ?bool
    {
        return $this->properties['commandOptions']['remoteSubmodules']['state'];
    }

    public function setRemoteSubmodules(?bool $value): static
    {
        $this->properties['commandOptions']['remoteSubmodules']['state'] = $value;

        return $this;
    }
}
