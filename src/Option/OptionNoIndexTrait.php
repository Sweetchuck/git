<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNoIndexTrait
{
    protected function initPropertyNoIndex(): static
    {
        $this->properties['commandOptions']['noIndex'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNoIndex(array $properties): static
    {
        if (array_key_exists('noIndex', $properties)) {
            $this->setNoIndex($properties['noIndex']);
        }

        return $this;
    }

    public function getNoIndex(): ?bool
    {
        return $this->properties['commandOptions']['noIndex']['state'];
    }

    public function setNoIndex(?bool $value): static
    {
        $this->properties['commandOptions']['noIndex']['state'] = $value;

        return $this;
    }
}
