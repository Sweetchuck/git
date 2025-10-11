<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionStageTrait
{
    protected function initPropertyStage(): static
    {
        $this->properties['commandOptions']['stage'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyStage(array $properties): static
    {
        if (array_key_exists('stage', $properties)) {
            $this->setStage($properties['stage']);
        }

        return $this;
    }

    public function getStage(): ?bool
    {
        return $this->properties['commandOptions']['stage']['state'];
    }

    public function setStage(?bool $value): static
    {
        $this->properties['commandOptions']['stage']['state'] = $value;

        return $this;
    }
}
