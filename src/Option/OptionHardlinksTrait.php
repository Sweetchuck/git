<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionHardlinksTrait
{
    protected function initPropertyHardlinks(): static
    {
        $this->properties['commandOptions']['hardlinks'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyHardlinks(array $properties): static
    {
        if (array_key_exists('hardlinks', $properties)) {
            $this->setHardlinks($properties['hardlinks']);
        }

        return $this;
    }

    public function getHardlinks(): ?bool
    {
        return $this->properties['commandOptions']['hardlinks']['state'];
    }

    public function setHardlinks(?bool $value): static
    {
        $this->properties['commandOptions']['hardlinks']['state'] = $value;

        return $this;
    }
}
