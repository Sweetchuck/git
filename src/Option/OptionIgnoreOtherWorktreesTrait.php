<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreOtherWorktreesTrait
{
    protected function initPropertyIgnoreOtherWorktrees(): static
    {
        $this->properties['commandOptions']['ignoreOtherWorktrees'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreOtherWorktrees(array $properties): static
    {
        if (array_key_exists('ignoreOtherWorktrees', $properties)) {
            $this->setIgnoreOtherWorktrees($properties['ignoreOtherWorktrees']);
        }

        return $this;
    }

    public function getIgnoreOtherWorktrees(): ?bool
    {
        return $this->properties['commandOptions']['ignoreOtherWorktrees']['state'];
    }

    public function setIgnoreOtherWorktrees(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreOtherWorktrees']['state'] = $value;

        return $this;
    }
}
