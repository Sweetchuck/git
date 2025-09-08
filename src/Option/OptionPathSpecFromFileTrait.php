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
        $this->properties['commandOptions']['pathSpecFileNul'] = [
            'type' => 'state:true',
            'name' => '--pathspec-file-nul',
            'state' => null,
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

        if (array_key_exists('pathSpecFileNul', $properties)) {
            $this->setPathSpecFileNul($properties['pathSpecFileNul']);
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

    public function getPathSpecFileNul(): ?bool
    {
        return $this->properties['commandOptions']['pathSpecFileNul']['state'];
    }

    public function setPathSpecFileNul(?bool $value): static
    {
        $this->properties['commandOptions']['pathSpecFileNul']['state'] = $value;

        return $this;
    }
}
