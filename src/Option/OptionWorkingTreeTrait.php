<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionWorkingTreeTrait
{
    protected function initPropertyWorkingTree(): static
    {
        $this->properties['commandOptions']['workingTree'] = [
            'type' => 'state:bool',
            'name' => '--worktree',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyWorkingTree(array $properties): static
    {
        if (array_key_exists('workingTree', $properties)) {
            $this->setWorkingTree($properties['workingTree']);
        }

        return $this;
    }

    public function getWorkingTree(): ?bool
    {
        return $this->properties['commandOptions']['workingTree']['state'];
    }

    public function setWorkingTree(?bool $value): static
    {
        $this->properties['commandOptions']['workingTree']['state'] = $value;

        return $this;
    }
}
