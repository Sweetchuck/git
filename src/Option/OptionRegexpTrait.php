<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRegexpTrait
{

    protected function initPropertyRegexp(): static
    {
        $this->properties['commandOptions']['regexp'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRegexp(array $properties): static
    {
        if (array_key_exists('regexp', $properties)) {
            $this->setRegexp($properties['regexp']);
        }

        return $this;
    }

    public function getRegexp(): ?bool
    {
        return $this->properties['commandOptions']['regexp']['state'];
    }

    public function setRegexp(?bool $value): static
    {
        $this->properties['commandOptions']['regexp']['state'] = $value;

        return $this;
    }
}
