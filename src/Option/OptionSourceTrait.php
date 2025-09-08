<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSourceTrait
{

    protected function initPropertySource(): static
    {
        $this->properties['commandOptions']['source'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySource(array $properties): static
    {
        if (array_key_exists('source', $properties)) {
            $this->setSource($properties['source']);
        }

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->properties['commandOptions']['source']['value'];
    }

    public function setSource(?string $value): static
    {
        $this->properties['commandOptions']['source']['value'] = $value;

        return $this;
    }
}
