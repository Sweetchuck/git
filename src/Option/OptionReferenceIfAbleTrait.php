<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionReferenceIfAbleTrait
{
    protected function initPropertyReferenceIfAble(): static
    {
        $this->properties['commandOptions']['referenceIfAble'] = [
            'type' => 'value:false:string-required',
            'name' => '--reference-if-able',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyReferenceIfAble(array $properties): static
    {
        if (array_key_exists('referenceIfAble', $properties)) {
            $this->setReferenceIfAble($properties['referenceIfAble']);
        }

        return $this;
    }

    public function getReferenceIfAble(): null|false|string
    {
        return $this->properties['commandOptions']['referenceIfAble']['value'];
    }

    public function setReferenceIfAble(null|false|string $value): static
    {
        $this->properties['commandOptions']['referenceIfAble']['value'] = $value;

        return $this;
    }
}
