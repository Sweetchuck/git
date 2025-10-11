<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFileTrait
{

    protected function initPropertyFile(): static
    {
        $this->properties['commandOptions']['file'] = [
            'type' => CommandOptionType::ValueTrueFalseString,
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

    public function getFile(): null|false|string
    {
        return $this->properties['commandOptions']['file']['value'];
    }

    public function setFile(null|false|string $value): static
    {
        $this->properties['commandOptions']['file']['value'] = $value;

        return $this;
    }
}
