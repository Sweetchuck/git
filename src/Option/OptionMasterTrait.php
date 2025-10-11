<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMasterTrait
{
    protected function initPropertyMaster(): static
    {
        $this->properties['commandOptions']['master'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMaster(array $properties): static
    {
        if (array_key_exists('master', $properties)) {
            $this->setMaster($properties['master']);
        }

        return $this;
    }

    public function getMaster(): null|false|string
    {
        return $this->properties['commandOptions']['master']['value'];
    }

    public function setMaster(null|false|string $value): static
    {
        $this->properties['commandOptions']['master']['value'] = $value;

        return $this;
    }
}
