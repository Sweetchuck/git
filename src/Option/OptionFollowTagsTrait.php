<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFollowTagsTrait
{
    protected function initPropertyFollowTags(): static
    {
        $this->properties['commandOptions']['followTags'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFollowTags(array $properties): static
    {
        if (array_key_exists('followTags', $properties)) {
            $this->setFollowTags($properties['followTags']);
        }

        return $this;
    }

    public function getFollowTags(): ?bool
    {
        return $this->properties['commandOptions']['followTags']['state'];
    }

    public function setFollowTags(?bool $value): static
    {
        $this->properties['commandOptions']['followTags']['state'] = $value;

        return $this;
    }
}
