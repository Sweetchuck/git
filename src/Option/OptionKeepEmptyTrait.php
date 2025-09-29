<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionKeepEmptyTrait
{

    protected function initPropertyKeepEmpty(): static
    {
        $this->properties['commandOptions']['keepEmpty'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyKeepEmpty(array $properties): static
    {
        if (array_key_exists('keepEmpty', $properties)) {
            $this->setKeepEmpty($properties['keepEmpty']);
        }

        return $this;
    }

    public function getKeepEmpty(): ?bool
    {
        return $this->properties['commandOptions']['keepEmpty']['state'];
    }

    public function setKeepEmpty(?bool $value): static
    {
        $this->properties['commandOptions']['keepEmpty']['state'] = $value;

        return $this;
    }
}
