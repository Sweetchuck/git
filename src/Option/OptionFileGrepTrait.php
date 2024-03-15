<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFileGrepTrait
{

    protected function initPropertyFile(): static
    {
        $this->properties['commandOptions']['file'] = [
            'type' => 'value:string-required',
            'name' => '-f',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFile(array $properties): static
    {
        if (array_key_exists('file', $properties)) {
            $this->setFile($properties['file']);
        }

        return $this;
    }

    public function getFile(): null|string
    {
        return $this->properties['commandOptions']['file']['value'];
    }

    public function setFile(null|string $value): static
    {
        $this->properties['commandOptions']['file']['value'] = $value;

        return $this;
    }
}
