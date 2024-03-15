<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUploadPackTrait
{
    protected function initPropertyUploadPack(): static
    {
        $this->properties['commandOptions']['uploadPack'] = [
            'type' => 'value:false:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUploadPack(array $properties): static
    {
        if (array_key_exists('uploadPack', $properties)) {
            $this->setUploadPack($properties['uploadPack']);
        }

        return $this;
    }

    public function getUploadPack(): null|false|string
    {
        return $this->properties['commandOptions']['uploadPack']['value'];
    }

    public function setUploadPack(null|false|string $value): static
    {
        $this->properties['commandOptions']['uploadPack']['value'] = $value;

        return $this;
    }
}
