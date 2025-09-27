<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionForceWithLeaseTrait
{
    protected function initPropertyForceWithLease(): static
    {
        $this->properties['commandOptions']['forceWithLease'] = [
            'type' => 'value:true-false:string',
            'value' => null,
        ];
        $this->properties['commandOptions']['forceIfIncludes'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyForceWithLease(array $properties): static
    {
        if (array_key_exists('forceWithLease', $properties)) {
            $this->setForceWithLease($properties['forceWithLease']);
        }

        if (array_key_exists('forceIfIncludes', $properties)) {
            $this->setForceIfIncludes($properties['forceIfIncludes']);
        }

        return $this;
    }

    public function getForceWithLease(): null|bool|string
    {
        return $this->properties['commandOptions']['forceWithLease']['value'];
    }

    public function setForceWithLease(null|bool|string $value): static
    {
        $this->properties['commandOptions']['forceWithLease']['value'] = $value;

        return $this;
    }

    public function getForceIfIncludes(): ?bool
    {
        return $this->properties['commandOptions']['forceIfIncludes']['state'];
    }

    public function setForceIfIncludes(?bool $value): static
    {
        $this->properties['commandOptions']['forceIfIncludes']['state'] = $value;

        return $this;
    }
}
