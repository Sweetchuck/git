<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRootTrait
{

    protected function initPropertyRoot(): static
    {
        $this->properties['commandOptions']['root'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRoot(array $properties): static
    {
        if (array_key_exists('root', $properties)) {
            $this->setRoot($properties['root']);
        }

        return $this;
    }

    public function getRoot(): ?bool
    {
        return $this->properties['commandOptions']['root']['state'];
    }

    public function setRoot(?bool $value): static
    {
        $this->properties['commandOptions']['root']['state'] = $value;

        return $this;
    }
}
