<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFixedValueTrait
{

    protected function initPropertyFixedValue(): static
    {
        $this->properties['commandOptions']['fixedValue'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFixedValue(array $properties): static
    {
        if (array_key_exists('fixedValue', $properties)) {
            $this->setFixedValue($properties['fixedValue']);
        }

        return $this;
    }

    public function getFixedValue(): ?bool
    {
        return $this->properties['commandOptions']['fixedValue']['state'];
    }

    public function setFixedValue(?bool $value): static
    {
        $this->properties['commandOptions']['fixedValue']['state'] = $value;

        return $this;
    }
}
