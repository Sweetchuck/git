<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSingleBranchTrait
{

    protected function initPropertySingleBranch(): static
    {
        $this->properties['commandOptions']['singleBranch'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySingleBranch(array $properties): static
    {
        if (array_key_exists('singleBranch', $properties)) {
            $this->setSingleBranch($properties['singleBranch']);
        }

        return $this;
    }

    public function getSingleBranch(): ?bool
    {
        return $this->properties['commandOptions']['singleBranch']['state'];
    }

    public function setSingleBranch(?bool $value): static
    {
        $this->properties['commandOptions']['singleBranch']['state'] = $value;

        return $this;
    }
}
