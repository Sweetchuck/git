<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreSkipWorktreeBitsTrait
{
    protected function initPropertyIgnoreSkipWorktreeBits(): static
    {
        $this->properties['commandOptions']['ignoreSkipWorktreeBits'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreSkipWorktreeBits(array $properties): static
    {
        if (array_key_exists('ignoreSkipWorktreeBits', $properties)) {
            $this->setIgnoreSkipWorktreeBits($properties['ignoreSkipWorktreeBits']);
        }

        return $this;
    }

    public function getIgnoreSkipWorktreeBits(): ?bool
    {
        return $this->properties['commandOptions']['ignoreSkipWorktreeBits']['state'];
    }

    public function setIgnoreSkipWorktreeBits(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreSkipWorktreeBits']['state'] = $value;

        return $this;
    }
}
