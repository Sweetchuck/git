<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMirrorRemoteAddTrait
{
    protected function initPropertyMirror(): static
    {
        $this->properties['commandOptions']['mirror'] = [
            'type' => CommandOptionType::ValueTrueFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMirror(array $properties): static
    {
        if (array_key_exists('mirror', $properties)) {
            $this->setMirror($properties['mirror']);
        }

        return $this;
    }

    /**
     * @phpstan-return null|bool|"pull"|"fetch"
     */
    public function getMirror(): null|bool|string
    {
        return $this->properties['commandOptions']['mirror']['value'];
    }

    /**
     * @phpstan-param null|bool|"pull"|"fetch" $value
     */
    public function setMirror(null|bool|string $value): static
    {
        $this->properties['commandOptions']['mirror']['value'] = $value;

        return $this;
    }
}
