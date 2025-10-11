<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExecTrait
{
    protected function initPropertyExec(): static
    {
        $this->properties['commandOptions']['exec'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExec(array $properties): static
    {
        if (array_key_exists('exec', $properties)) {
            $this->setExec($properties['exec']);
        }

        return $this;
    }

    public function getExec(): null|false|string
    {
        return $this->properties['commandOptions']['exec']['value'];
    }

    public function setExec(null|false|string $value): static
    {
        $this->properties['commandOptions']['exec']['value'] = $value;

        return $this;
    }
}
