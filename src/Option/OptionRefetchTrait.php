<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRefetchTrait
{

    protected function initPropertyRefetch(): static
    {
        $this->properties['commandOptions']['refetch'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRefetch(array $properties): static
    {
        if (array_key_exists('refetch', $properties)) {
            $this->setRefetch($properties['refetch']);
        }

        return $this;
    }

    public function getRefetch(): ?bool
    {
        return $this->properties['commandOptions']['refetch']['state'];
    }

    public function setRefetch(?bool $value): static
    {
        $this->properties['commandOptions']['refetch']['state'] = $value;

        return $this;
    }
}
