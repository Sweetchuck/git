<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMergeBaseTrait
{
    protected function initPropertyMergeBase(): static
    {
        $this->properties['commandOptions']['mergeBase'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMergeBase(array $properties): static
    {
        if (array_key_exists('mergeBase', $properties)) {
            $this->setMergeBase($properties['mergeBase']);
        }

        return $this;
    }

    public function getMergeBase(): ?bool
    {
        return $this->properties['commandOptions']['mergeBase']['state'];
    }

    public function setMergeBase(?bool $value): static
    {
        $this->properties['commandOptions']['mergeBase']['state'] = $value;

        return $this;
    }
}
