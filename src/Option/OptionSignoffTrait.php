<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSignoffTrait
{
    protected function initPropertySignoff(): static
    {
        $this->properties['commandOptions']['signoff'] = [
            'type' => 'state:true',
            'short' => '-s',
            'name' => '--signoff',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySignoff(array $properties): static
    {
        if (array_key_exists('signoff', $properties)) {
            $this->setSignoff($properties['signoff']);
        }

        return $this;
    }

    public function getSignoff(): ?bool
    {
        return $this->properties['commandOptions']['signoff']['state'];
    }

    public function setSignoff(?bool $value): static
    {
        $this->properties['commandOptions']['signoff']['state'] = $value;

        return $this;
    }
}
