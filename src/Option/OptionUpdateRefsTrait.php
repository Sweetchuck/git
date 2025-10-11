<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUpdateRefsTrait
{

    protected function initPropertyUpdateRefs(): static
    {
        $this->properties['commandOptions']['updateRefs'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUpdateRefs(array $properties): static
    {
        if (array_key_exists('updateRefs', $properties)) {
            $this->setUpdateRefs($properties['updateRefs']);
        }

        return $this;
    }

    public function getUpdateRefs(): ?bool
    {
        return $this->properties['commandOptions']['updateRefs']['state'];
    }

    public function setUpdateRefs(?bool $value): static
    {
        $this->properties['commandOptions']['updateRefs']['state'] = $value;

        return $this;
    }
}
