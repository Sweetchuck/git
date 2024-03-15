<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionCachedTrait
{
    protected function initPropertyCached(): static
    {
        $this->properties['commandOptions']['cached'] = [
            'type' => 'state:true',
            'name' => '--cached',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyCached(array $properties): static
    {
        if (array_key_exists('cached', $properties)) {
            $this->setCached($properties['cached']);
        }

        return $this;
    }

    public function getCached(): ?bool
    {
        return $this->properties['commandOptions']['cached']['state'];
    }

    public function setCached(?bool $value): static
    {
        $this->properties['commandOptions']['cached']['state'] = $value;

        return $this;
    }
}
