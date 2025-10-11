<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionTextConvTrait
{
    protected function initPropertyTextConv(): static
    {
        $this->properties['commandOptions']['textConv'] = [
            'type' => CommandOptionType::StateTrue,
            'name' => '--textconv',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyTextConv(array $properties): static
    {
        if (array_key_exists('textConv', $properties)) {
            $this->setTextConv($properties['textConv']);
        }

        return $this;
    }

    public function getTextConv(): ?bool
    {
        return $this->properties['commandOptions']['textConv']['state'];
    }

    public function setTextConv(?bool $value): static
    {
        $this->properties['commandOptions']['textConv']['state'] = $value;

        return $this;
    }
}
