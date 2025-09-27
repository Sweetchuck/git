<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSkipTrait
{
    protected function initPropertySkip(): static
    {
        $this->properties['commandOptions']['skip'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySkip(array $properties): static
    {
        if (array_key_exists('skip', $properties)) {
            $this->setSkip($properties['skip']);
        }

        return $this;
    }

    public function getSkip(): ?int
    {
        return $this->properties['commandOptions']['skip']['value'];
    }

    public function setSkip(?int $value): static
    {
        $this->properties['commandOptions']['skip']['value'] = $value;

        return $this;
    }
}
