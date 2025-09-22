<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionVerifyTrait
{
    protected function initPropertyVerify(): static
    {
        $this->properties['commandOptions']['verify'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyVerify(array $properties): static
    {
        if (array_key_exists('verify', $properties)) {
            $this->setVerify($properties['verify']);
        }

        return $this;
    }

    public function getVerify(): ?bool
    {
        return $this->properties['commandOptions']['verify']['state'];
    }

    public function setVerify(?bool $value): static
    {
        $this->properties['commandOptions']['verify']['state'] = $value;

        return $this;
    }
}
