<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionShowForcedUpdatesTrait
{

    protected function initPropertyShowForcedUpdates(): static
    {
        $this->properties['commandOptions']['showForcedUpdates'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShowForcedUpdates(array $properties): static
    {
        if (array_key_exists('showForcedUpdates', $properties)) {
            $this->setShowForcedUpdates($properties['showForcedUpdates']);
        }

        return $this;
    }

    public function getShowForcedUpdates(): ?bool
    {
        return $this->properties['commandOptions']['showForcedUpdates']['state'];
    }

    public function setShowForcedUpdates(?bool $value): static
    {
        $this->properties['commandOptions']['showForcedUpdates']['state'] = $value;

        return $this;
    }
}
