<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionWordRegexpTrait
{
    protected function initPropertyWordRegexp(): static
    {
        $this->properties['commandOptions']['wordRegexp'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyWordRegexp(array $properties): static
    {
        if (array_key_exists('wordRegexp', $properties)) {
            $this->setWordRegexp($properties['wordRegexp']);
        }

        return $this;
    }

    public function getWordRegexp(): ?bool
    {
        return $this->properties['commandOptions']['wordRegexp']['state'];
    }

    public function setWordRegexp(?bool $value): static
    {
        $this->properties['commandOptions']['wordRegexp']['state'] = $value;

        return $this;
    }
}
