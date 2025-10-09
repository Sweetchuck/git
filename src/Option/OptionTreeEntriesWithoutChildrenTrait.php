<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionTreeEntriesWithoutChildrenTrait
{

    protected function initPropertyTreeEntriesWithoutChildren(): static
    {
        $this->properties['commandOptions']['treeEntriesWithoutChildren'] = [
            'type' => 'state:true',
            'name' => '-d',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyTreeEntriesWithoutChildren(array $properties): static
    {
        if (array_key_exists('treeEntriesWithoutChildren', $properties)) {
            $this->setTreeEntriesWithoutChildren($properties['treeEntriesWithoutChildren']);
        }

        return $this;
    }

    public function getTreeEntriesWithoutChildren(): ?bool
    {
        return $this->properties['commandOptions']['treeEntriesWithoutChildren']['state'];
    }

    public function setTreeEntriesWithoutChildren(?bool $value): static
    {
        $this->properties['commandOptions']['treeEntriesWithoutChildren']['state'] = $value;

        return $this;
    }
}
