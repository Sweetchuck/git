<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionShowFunctionTrait
{
    protected function initPropertyShowFunction(): static
    {
        $this->properties['commandOptions']['showFunction'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShowFunction(array $properties): static
    {
        if (array_key_exists('showFunction', $properties)) {
            $this->setShowFunction($properties['showFunction']);
        }

        return $this;
    }

    public function getShowFunction(): ?bool
    {
        return $this->properties['commandOptions']['showFunction']['state'];
    }

    public function setShowFunction(?bool $value): static
    {
        $this->properties['commandOptions']['showFunction']['state'] = $value;

        return $this;
    }
}
