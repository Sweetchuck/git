<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOthersTrait
{
    protected function initPropertyOthers(): static
    {
        $this->properties['commandOptions']['others'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOthers(array $properties): static
    {
        if (array_key_exists('others', $properties)) {
            $this->setOthers($properties['others']);
        }

        return $this;
    }

    public function getOthers(): ?bool
    {
        return $this->properties['commandOptions']['others']['state'];
    }

    public function setOthers(?bool $value): static
    {
        $this->properties['commandOptions']['others']['state'] = $value;

        return $this;
    }
}
