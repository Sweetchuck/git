<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionEmptyTrait
{
    protected function initPropertyEmpty(): static
    {
        $this->properties['commandOptions']['empty'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyEmpty(array $properties): static
    {
        if (array_key_exists('empty', $properties)) {
            $this->setEmpty($properties['empty']);
        }

        return $this;
    }

    public function getEmpty(): null|string
    {
        return $this->properties['commandOptions']['empty']['value'];
    }

    /**
     * @todo Use \Sweetchuck\Git\Empty enum.
     */
    public function setEmpty(null|string $value): static
    {
        $this->properties['commandOptions']['empty']['value'] = $value;

        return $this;
    }
}
