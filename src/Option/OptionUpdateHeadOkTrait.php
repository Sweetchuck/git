<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionUpdateHeadOkTrait
{

    protected function initPropertyUpdateHeadOk(): static
    {
        $this->properties['commandOptions']['updateHeadOk'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyUpdateHeadOk(array $properties): static
    {
        if (array_key_exists('updateHeadOk', $properties)) {
            $this->setUpdateHeadOk($properties['updateHeadOk']);
        }

        return $this;
    }

    public function getUpdateHeadOk(): ?bool
    {
        return $this->properties['commandOptions']['updateHeadOk']['state'];
    }

    public function setUpdateHeadOk(?bool $value): static
    {
        $this->properties['commandOptions']['updateHeadOk']['state'] = $value;

        return $this;
    }
}
