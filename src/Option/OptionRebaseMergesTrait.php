<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRebaseMergesTrait
{
    protected function initPropertyRebaseMerges(): static
    {
        $this->properties['commandOptions']['rebaseMerges'] = [
            'type' => CommandOptionType::ValueTrueFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRebaseMerges(array $properties): static
    {
        if (array_key_exists('rebaseMerges', $properties)) {
            $this->setRebaseMerges($properties['rebaseMerges']);
        }

        return $this;
    }

    public function getRebaseMerges(): null|bool|string
    {
        return $this->properties['commandOptions']['rebaseMerges']['value'];
    }

    public function setRebaseMerges(null|bool|string $value): static
    {
        $this->properties['commandOptions']['rebaseMerges']['value'] = $value;

        return $this;
    }
}
