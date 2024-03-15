<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNoSignTrait
{
    protected function initPropertyNoSign(): static
    {
        $this->properties['commandOptions']['noSign'] = [
            'type' => 'state:true',
            'name' => '--no-sign',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNoSign(array $properties): static
    {
        if (array_key_exists('noSign', $properties)) {
            $this->setNoSign($properties['noSign']);
        }

        return $this;
    }

    public function getNoSign(): ?bool
    {
        return $this->properties['commandOptions']['noSign']['state'];
    }

    public function setNoSign(?bool $value): static
    {
        $this->properties['commandOptions']['noSign']['state'] = $value;

        return $this;
    }
}
