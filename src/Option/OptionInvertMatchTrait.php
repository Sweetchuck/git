<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionInvertMatchTrait
{
    protected function initPropertyInvertMatch(): static
    {
        $this->properties['commandOptions']['invertMatch'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyInvertMatch(array $properties): static
    {
        if (array_key_exists('invertMatch', $properties)) {
            $this->setInvertMatch($properties['invertMatch']);
        }

        return $this;
    }

    public function getInvertMatch(): ?bool
    {
        return $this->properties['commandOptions']['invertMatch']['state'];
    }

    public function setInvertMatch(?bool $value): static
    {
        $this->properties['commandOptions']['invertMatch']['state'] = $value;

        return $this;
    }
}
