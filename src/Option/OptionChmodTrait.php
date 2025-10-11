<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionChmodTrait
{
    protected function initPropertyChmod(): static
    {
        $this->properties['commandOptions']['chmod'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyChmod(array $properties): static
    {
        if (array_key_exists('chmod', $properties)) {
            $this->setChmod($properties['chmod']);
        }

        return $this;
    }

    public function getChmod(): null|false|string
    {
        return $this->properties['commandOptions']['chmod']['value'];
    }

    public function setChmod(null|false|string $value): static
    {
        $this->properties['commandOptions']['chmod']['value'] = $value;

        return $this;
    }
}
