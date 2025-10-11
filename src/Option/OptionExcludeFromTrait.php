<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExcludeFromTrait
{

    protected function initPropertyExcludeFrom(): static
    {
        $this->properties['commandOptions']['excludeFrom'] = [
            'type' => CommandOptionType::ValueStringMultiple,
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExcludeFrom(array $properties): static
    {
        if (array_key_exists('excludeFrom', $properties)) {
            $this->setExcludeFrom($properties['excludeFrom']);
        }

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    public function getExcludeFrom(): array
    {
        return $this->properties['commandOptions']['excludeFrom']['value'];
    }

    /**
     * @param array<string, bool> $value
     */
    public function setExcludeFrom(array $value): static
    {
        $this->properties['commandOptions']['excludeFrom']['value'] = $value;

        return $this;
    }
}
