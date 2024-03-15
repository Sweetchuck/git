<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIgnoreBinaryFilesTrait
{

    protected function initPropertyIgnoreBinaryFiles(): static
    {
        $this->properties['commandOptions']['ignoreBinaryFiles'] = [
            'type' => 'state:true',
            'name' => '-I',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIgnoreBinaryFiles(array $properties): static
    {
        if (array_key_exists('ignoreBinaryFiles', $properties)) {
            $this->setIgnoreBinaryFiles($properties['ignoreBinaryFiles']);
        }

        return $this;
    }

    public function getIgnoreBinaryFiles(): ?bool
    {
        return $this->properties['commandOptions']['ignoreBinaryFiles']['state'];
    }

    public function setIgnoreBinaryFiles(?bool $value): static
    {
        $this->properties['commandOptions']['ignoreBinaryFiles']['state'] = $value;

        return $this;
    }
}
