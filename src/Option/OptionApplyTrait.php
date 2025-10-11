<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionApplyTrait
{

    protected function initPropertyApply(): static
    {
        $this->properties['commandOptions']['apply'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyApply(array $properties): static
    {
        if (array_key_exists('apply', $properties)) {
            $this->setApply($properties['apply']);
        }

        return $this;
    }

    public function getApply(): ?bool
    {
        return $this->properties['commandOptions']['apply']['state'];
    }

    public function setApply(?bool $value): static
    {
        $this->properties['commandOptions']['apply']['state'] = $value;

        return $this;
    }
}
