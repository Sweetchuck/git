<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionBranchTrait
{
    protected function initPropertyBranch(): static
    {
        $this->properties['commandOptions']['branch'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyBranch(array $properties): static
    {
        if (array_key_exists('branch', $properties)) {
            $this->setBranch($properties['branch']);
        }

        return $this;
    }

    public function getBranch(): null|false|string
    {
        return $this->properties['commandOptions']['branch']['value'];
    }

    public function setBranch(null|false|string $value): static
    {
        $this->properties['commandOptions']['branch']['value'] = $value;

        return $this;
    }
}
