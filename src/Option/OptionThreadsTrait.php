<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionThreadsTrait
{

    protected function initPropertyThreads(): static
    {
        $this->properties['commandOptions']['threads'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyThreads(array $properties): static
    {
        if (array_key_exists('threads', $properties)) {
            $this->setThreads($properties['threads']);
        }

        return $this;
    }

    public function getThreads(): ?int
    {
        return $this->properties['commandOptions']['threads']['value'];
    }

    public function setThreads(?int $value): static
    {
        $this->properties['commandOptions']['threads']['value'] = $value;

        return $this;
    }
}
