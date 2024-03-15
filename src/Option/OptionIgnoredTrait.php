<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoredTrait
{
    protected function initPropertyIgnored(): static
    {
        $this->properties['commandOptions']['ignored'] = [
            'type' => 'state:true',
            'name' => '--ignored',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnored(array $properties): static
    {
        if (array_key_exists('ignored', $properties)) {
            $this->setIgnored($properties['ignored']);
        }

        return $this;
    }

    public function getIgnored(): ?bool
    {
        return $this->properties['commandOptions']['ignored']['state'];
    }

    public function setIgnored(?bool $value): static
    {
        $this->properties['commandOptions']['ignored']['state'] = $value;

        return $this;
    }
}
