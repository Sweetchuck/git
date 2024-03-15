<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRefreshTrait
{
    protected function initPropertyRefresh(): static
    {
        $this->properties['commandOptions']['refresh'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRefresh(array $properties): static
    {
        if (array_key_exists('refresh', $properties)) {
            $this->setRefresh($properties['refresh']);
        }

        return $this;
    }

    public function getRefresh(): ?bool
    {
        return $this->properties['commandOptions']['refresh']['state'];
    }

    public function setRefresh(?bool $value): static
    {
        $this->properties['commandOptions']['refresh']['state'] = $value;

        return $this;
    }
}
