<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPrefetchTrait
{

    protected function initPropertyPrefetch(): static
    {
        $this->properties['commandOptions']['prefetch'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPrefetch(array $properties): static
    {
        if (array_key_exists('prefetch', $properties)) {
            $this->setPrefetch($properties['prefetch']);
        }

        return $this;
    }

    public function getPrefetch(): ?bool
    {
        return $this->properties['commandOptions']['prefetch']['state'];
    }

    public function setPrefetch(?bool $value): static
    {
        $this->properties['commandOptions']['prefetch']['state'] = $value;

        return $this;
    }
}
