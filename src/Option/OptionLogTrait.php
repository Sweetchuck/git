<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionLogTrait
{
    protected function initPropertyLog(): static
    {
        $this->properties['commandOptions']['log'] = [
            'type' => CommandOptionType::ValueTrueFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyLog(array $properties): static
    {
        if (array_key_exists('log', $properties)) {
            $this->setLog($properties['log']);
        }

        return $this;
    }

    public function getLog(): null|bool|int
    {
        return $this->properties['commandOptions']['log']['value'];
    }

    public function setLog(null|bool|int $value): static
    {
        $this->properties['commandOptions']['log']['value'] = $value;

        return $this;
    }
}
