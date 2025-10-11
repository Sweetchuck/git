<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMaxCountTrait
{

    protected function initPropertyMaxCount(): static
    {
        $this->properties['commandOptions']['maxCount'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMaxCount(array $properties): static
    {
        if (array_key_exists('maxCount', $properties)) {
            $this->setMaxCount($properties['maxCount']);
        }

        return $this;
    }

    public function getMaxCount(): null|int
    {
        return $this->properties['commandOptions']['maxCount']['value'];
    }

    public function setMaxCount(null|int $value): static
    {
        $this->properties['commandOptions']['maxCount']['value'] = $value;

        return $this;
    }
}
