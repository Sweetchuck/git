<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMaxDepthTrait
{

    protected function initPropertyMaxDepth(): static
    {
        $this->properties['commandOptions']['maxDepth'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMaxDepth(array $properties): static
    {
        if (array_key_exists('maxDepth', $properties)) {
            $this->setMaxDepth($properties['maxDepth']);
        }

        return $this;
    }

    public function getMaxDepth(): ?int
    {
        return $this->properties['commandOptions']['maxDepth']['value'];
    }

    public function setMaxDepth(?int $value): static
    {
        $this->properties['commandOptions']['maxDepth']['value'] = $value;

        return $this;
    }
}
