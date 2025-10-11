<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionJobsTrait
{

    protected function initPropertyJobs(): static
    {
        $this->properties['commandOptions']['jobs'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyJobs(array $properties): static
    {
        if (array_key_exists('jobs', $properties)) {
            $this->setJobs($properties['jobs']);
        }

        return $this;
    }

    public function getJobs(): null|false|int
    {
        return $this->properties['commandOptions']['jobs']['value'];
    }

    public function setJobs(null|false|int $value): static
    {
        $this->properties['commandOptions']['jobs']['value'] = $value;

        return $this;
    }
}
