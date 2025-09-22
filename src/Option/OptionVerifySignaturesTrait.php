<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionVerifySignaturesTrait
{
    protected function initPropertyVerifySignatures(): static
    {
        $this->properties['commandOptions']['verifySignatures'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyVerifySignatures(array $properties): static
    {
        if (array_key_exists('verifySignatures', $properties)) {
            $this->setVerifySignatures($properties['verifySignatures']);
        }

        return $this;
    }

    public function getVerifySignatures(): ?bool
    {
        return $this->properties['commandOptions']['verifySignatures']['state'];
    }

    public function setVerifySignatures(?bool $value): static
    {
        $this->properties['commandOptions']['verifySignatures']['state'] = $value;

        return $this;
    }
}
