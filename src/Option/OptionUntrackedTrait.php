<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUntrackedTrait
{
    protected function initPropertyUntracked(): static
    {
        $this->properties['commandOptions']['untracked'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUntracked(array $properties): static
    {
        if (array_key_exists('untracked', $properties)) {
            $this->setUntracked($properties['untracked']);
        }

        return $this;
    }

    public function getUntracked(): ?bool
    {
        return $this->properties['commandOptions']['untracked']['state'];
    }

    public function setUntracked(?bool $value): static
    {
        $this->properties['commandOptions']['untracked']['state'] = $value;

        return $this;
    }
}
