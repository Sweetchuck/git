<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAlsoFilterSubmodulesTrait
{
    protected function initPropertyAlsoFilterSubmodules(): static
    {
        $this->properties['commandOptions']['alsoFilterSubmodules'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAlsoFilterSubmodules(array $properties): static
    {
        if (array_key_exists('alsoFilterSubmodules', $properties)) {
            $this->setAlsoFilterSubmodules($properties['alsoFilterSubmodules']);
        }

        return $this;
    }

    public function getAlsoFilterSubmodules(): ?bool
    {
        return $this->properties['commandOptions']['alsoFilterSubmodules']['state'];
    }

    public function setAlsoFilterSubmodules(?bool $value): static
    {
        $this->properties['commandOptions']['alsoFilterSubmodules']['state'] = $value;

        return $this;
    }
}
