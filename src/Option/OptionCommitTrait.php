<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionCommitTrait
{
    protected function initPropertyCommit(): static
    {
        $this->properties['commandOptions']['commit'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyCommit(array $properties): static
    {
        if (array_key_exists('commit', $properties)) {
            $this->setCommit($properties['commit']);
        }

        return $this;
    }

    public function getCommit(): ?bool
    {
        return $this->properties['commandOptions']['commit']['state'];
    }

    public function setCommit(?bool $value): static
    {
        $this->properties['commandOptions']['commit']['state'] = $value;

        return $this;
    }
}
