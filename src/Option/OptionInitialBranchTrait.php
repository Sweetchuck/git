<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionInitialBranchTrait
{
    protected function initPropertyInitialBranch(): static
    {
        $this->properties['commandOptions']['initialBranch'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyInitialBranch(array $properties): static
    {
        if (array_key_exists('initialBranch', $properties)) {
            $this->setInitialBranch($properties['initialBranch']);
        }

        return $this;
    }

    public function getInitialBranch(): ?string
    {
        return $this->properties['commandOptions']['initialBranch']['value'];
    }

    public function setInitialBranch(?string $value): static
    {
        $this->properties['commandOptions']['initialBranch']['value'] = $value;

        return $this;
    }
}
