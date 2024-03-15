<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRefFormatTrait
{
    protected function initPropertyRefFormat(): static
    {
        $this->properties['commandOptions']['refFormat'] = [
            'type' => 'value:false:string-required',
            'name' => '--ref-format',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRefFormat(array $properties): static
    {
        if (array_key_exists('refFormat', $properties)) {
            $this->setRefFormat($properties['refFormat']);
        }

        return $this;
    }

    public function getRefFormat(): null|false|string
    {
        return $this->properties['commandOptions']['refFormat']['value'];
    }

    public function setRefFormat(null|false|string $value): static
    {
        $this->properties['commandOptions']['refFormat']['value'] = $value;

        return $this;
    }
}
