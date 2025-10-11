<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRebaseTrait
{
    protected function initPropertyRebase(): static
    {
        $this->properties['commandOptions']['rebase'] = [
            'type' => CommandOptionType::ValueTrueFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRebase(array $properties): static
    {
        if (array_key_exists('rebase', $properties)) {
            $this->setRebase($properties['rebase']);
        }

        return $this;
    }

    public function getRebase(): null|bool|string
    {
        return $this->properties['commandOptions']['rebase']['value'];
    }

    public function setRebase(null|bool|string $value): static
    {
        $this->properties['commandOptions']['rebase']['value'] = $value;

        return $this;
    }
}
