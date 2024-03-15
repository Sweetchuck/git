<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPathSpecFromFileTrait
{
    protected function initPropertyPathSpecFromFile(): static
    {
        $this->properties['commandOptions']['pathSpecFromFile'] = [
            'type' => 'value:false:string-required',
            'name' => '--pathspec-from-file',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPathSpecFromFile(array $properties): static
    {
        if (array_key_exists('pathSpecFromFile', $properties)) {
            $this->setPathSpecFromFile($properties['pathSpecFromFile']);
        }

        return $this;
    }

    public function getPathSpecFromFile(): null|false|string
    {
        return $this->properties['commandOptions']['pathSpecFromFile']['value'];
    }

    public function setPathSpecFromFile(null|false|string $value): static
    {
        $this->properties['commandOptions']['pathSpecFromFile']['value'] = $value;

        return $this;
    }
}
