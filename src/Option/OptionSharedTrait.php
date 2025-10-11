<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSharedTrait
{
    protected function initPropertyShared(): static
    {
        $this->properties['commandOptions']['shared'] = [
            'type' => CommandOptionType::ValueTrueFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShared(array $properties): static
    {
        if (array_key_exists('shared', $properties)) {
            $this->setShared($properties['shared']);
        }

        return $this;
    }

    public function getShared(): null|bool|string
    {
        return $this->properties['commandOptions']['shared']['value'];
    }

    public function setShared(null|bool|string $value): static
    {
        $this->properties['commandOptions']['shared']['value'] = $value;

        return $this;
    }
}
