<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionTagsTrait
{

    protected function initPropertyTags(): static
    {
        $this->properties['commandOptions']['tags'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyTags(array $properties): static
    {
        if (array_key_exists('tags', $properties)) {
            $this->setTags($properties['tags']);
        }

        return $this;
    }

    public function getTags(): ?bool
    {
        return $this->properties['commandOptions']['tags']['state'];
    }

    public function setTags(?bool $value): static
    {
        $this->properties['commandOptions']['tags']['state'] = $value;

        return $this;
    }
}
