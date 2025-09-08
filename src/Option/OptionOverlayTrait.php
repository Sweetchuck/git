<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionOverlayTrait
{
    protected function initPropertyOverlay(): static
    {
        $this->properties['commandOptions']['overlay'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyOverlay(array $properties): static
    {
        if (array_key_exists('overlay', $properties)) {
            $this->setOverlay($properties['overlay']);
        }

        return $this;
    }

    public function getOverlay(): ?bool
    {
        return $this->properties['commandOptions']['overlay']['state'];
    }

    public function setOverlay(?bool $value): static
    {
        $this->properties['commandOptions']['overlay']['state'] = $value;

        return $this;
    }
}
