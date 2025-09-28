<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAutoMaintenanceTrait
{

    protected function initPropertyAutoMaintenance(): static
    {
        $this->properties['commandOptions']['autoMaintenance'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAutoMaintenance(array $properties): static
    {
        if (array_key_exists('autoMaintenance', $properties)) {
            $this->setAutoMaintenance($properties['autoMaintenance']);
        }

        return $this;
    }

    public function getAutoMaintenance(): ?bool
    {
        return $this->properties['commandOptions']['autoMaintenance']['state'];
    }

    public function setAutoMaintenance(?bool $value): static
    {
        $this->properties['commandOptions']['autoMaintenance']['state'] = $value;

        return $this;
    }
}
