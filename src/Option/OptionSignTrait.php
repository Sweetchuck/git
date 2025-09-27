<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSignTrait
{
    protected function initPropertySign(): static
    {
        $this->properties['commandOptions']['sign'] = [
            'type' => 'state:true',
            'short' => '-s',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySign(array $properties): static
    {
        if (array_key_exists('sign', $properties)) {
            $this->setSign($properties['sign']);
        }

        return $this;
    }

    public function getSign(): ?bool
    {
        return $this->properties['commandOptions']['sign']['state'];
    }

    public function setSign(?bool $value): static
    {
        $this->properties['commandOptions']['sign']['state'] = $value;

        return $this;
    }
}
