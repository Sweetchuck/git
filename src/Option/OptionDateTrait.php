<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionDateTrait
{
    protected function initPropertyDate(): static
    {
        $this->properties['commandOptions']['date'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDate(array $properties): static
    {
        if (array_key_exists('date', $properties)) {
            $this->setDate($properties['date']);
        }

        return $this;
    }

    public function getDate(): ?string
    {
        return $this->properties['commandOptions']['date']['value'];
    }

    public function setDate(?string $value): static
    {
        $this->properties['commandOptions']['date']['value'] = $value;

        return $this;
    }
}
