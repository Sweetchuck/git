<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNoWarnEmbeddedRepoTrait
{
    protected function initPropertyNoWarnEmbeddedRepo(): static
    {
        $this->properties['commandOptions']['noWarnEmbeddedRepo'] = [
            'type' => 'state:true',
            'name' => '--no-warn-embedded-repo',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNoWarnEmbeddedRepo(array $properties): static
    {
        if (array_key_exists('noWarnEmbeddedRepo', $properties)) {
            $this->setNoWarnEmbeddedRepo($properties['noWarnEmbeddedRepo']);
        }

        return $this;
    }

    public function getNoWarnEmbeddedRepo(): ?bool
    {
        return $this->properties['commandOptions']['noWarnEmbeddedRepo']['state'];
    }

    public function setNoWarnEmbeddedRepo(?bool $value): static
    {
        $this->properties['commandOptions']['noWarnEmbeddedRepo']['state'] = $value;

        return $this;
    }
}
