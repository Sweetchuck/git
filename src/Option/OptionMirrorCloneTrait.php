<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMirrorCloneTrait
{
    protected function initPropertyMirror(): static
    {
        $this->properties['commandOptions']['mirror'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMirror(array $properties): static
    {
        if (array_key_exists('mirror', $properties)) {
            $this->setMirror($properties['mirror']);
        }

        return $this;
    }

    public function getMirror(): ?bool
    {
        return $this->properties['commandOptions']['mirror']['state'];
    }

    public function setMirror(?bool $value): static
    {
        $this->properties['commandOptions']['mirror']['state'] = $value;

        return $this;
    }
}
