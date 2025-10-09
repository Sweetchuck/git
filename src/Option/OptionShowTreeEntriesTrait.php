<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionShowTreeEntriesTrait
{

    protected function initPropertyShowTreeEntries(): static
    {
        $this->properties['commandOptions']['showTreeEntries'] = [
            'type' => 'state:true',
            'name' => '-t',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyShowTreeEntries(array $properties): static
    {
        if (array_key_exists('showTreeEntries', $properties)) {
            $this->setShowTreeEntries($properties['showTreeEntries']);
        }

        return $this;
    }

    public function getShowTreeEntries(): ?bool
    {
        return $this->properties['commandOptions']['showTreeEntries']['state'];
    }

    public function setShowTreeEntries(?bool $value): static
    {
        $this->properties['commandOptions']['showTreeEntries']['state'] = $value;

        return $this;
    }
}
