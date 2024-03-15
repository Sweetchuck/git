<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDetachTrait
{
    protected function initPropertyDetach(): static
    {
        $this->properties['commandOptions']['detach'] = [
            'type' => 'state:true',
            'name' => '--detach',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDetach(array $properties): static
    {
        if (array_key_exists('detach', $properties)) {
            $this->setDetach($properties['detach']);
        }

        return $this;
    }

    public function getDetach(): ?bool
    {
        return $this->properties['commandOptions']['detach']['state'];
    }

    public function setDetach(?bool $value): static
    {
        $this->properties['commandOptions']['detach']['state'] = $value;

        return $this;
    }
}
