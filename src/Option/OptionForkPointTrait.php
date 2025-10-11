<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionForkPointTrait
{

    protected function initPropertyForkPoint(): static
    {
        $this->properties['commandOptions']['forkPoint'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyForkPoint(array $properties): static
    {
        if (array_key_exists('forkPoint', $properties)) {
            $this->setForkPoint($properties['forkPoint']);
        }

        return $this;
    }

    public function getForkPoint(): ?bool
    {
        return $this->properties['commandOptions']['forkPoint']['state'];
    }

    public function setForkPoint(?bool $value): static
    {
        $this->properties['commandOptions']['forkPoint']['state'] = $value;

        return $this;
    }
}
