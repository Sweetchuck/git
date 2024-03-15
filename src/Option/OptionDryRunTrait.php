<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDryRunTrait
{
    protected function initPropertyDryRun(): static
    {
        $this->properties['commandOptions']['dryRun'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDryRun(array $properties): static
    {
        if (array_key_exists('dryRun', $properties)) {
            $this->setDryRun($properties['dryRun']);
        }

        return $this;
    }

    public function getDryRun(): ?bool
    {
        return $this->properties['commandOptions']['dryRun']['state'];
    }

    public function setDryRun(?bool $value): static
    {
        $this->properties['commandOptions']['dryRun']['state'] = $value;

        return $this;
    }
}
