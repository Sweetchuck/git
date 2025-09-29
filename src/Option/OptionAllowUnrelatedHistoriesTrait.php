<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAllowUnrelatedHistoriesTrait
{

    protected function initPropertyAllowUnrelatedHistories(): static
    {
        $this->properties['commandOptions']['allowUnrelatedHistories'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAllowUnrelatedHistories(array $properties): static
    {
        if (array_key_exists('allowUnrelatedHistories', $properties)) {
            $this->setAllowUnrelatedHistories($properties['allowUnrelatedHistories']);
        }

        return $this;
    }

    public function getAllowUnrelatedHistories(): ?bool
    {
        return $this->properties['commandOptions']['allowUnrelatedHistories']['state'];
    }

    public function setAllowUnrelatedHistories(?bool $value): static
    {
        $this->properties['commandOptions']['allowUnrelatedHistories']['state'] = $value;

        return $this;
    }
}
