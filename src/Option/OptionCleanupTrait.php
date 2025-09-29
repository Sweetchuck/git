<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionCleanupTrait
{
    protected function initPropertyCleanup(): static
    {
        $this->properties['commandOptions']['cleanup'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyCleanup(array $properties): static
    {
        if (array_key_exists('cleanup', $properties)) {
            $this->setCleanup($properties['cleanup']);
        }

        return $this;
    }

    public function getCleanup(): null|string
    {
        return $this->properties['commandOptions']['cleanup']['value'];
    }

    /**
     * @todo Use \Sweetchuck\Git\Cleanup enum.
     */
    public function setCleanup(null|string $value): static
    {
        $this->properties['commandOptions']['cleanup']['value'] = $value;

        return $this;
    }
}
