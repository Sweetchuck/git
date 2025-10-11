<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNoVerifyTrait
{
    protected function initPropertyNoVerify(): static
    {
        $this->properties['commandOptions']['noVerify'] = [
            'type' => CommandOptionType::StateTrue,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNoVerify(array $properties): static
    {
        if (array_key_exists('noVerify', $properties)) {
            $this->setNoVerify($properties['noVerify']);
        }

        return $this;
    }

    public function getNoVerify(): ?bool
    {
        return $this->properties['commandOptions']['noVerify']['state'];
    }

    public function setNoVerify(?bool $value): static
    {
        $this->properties['commandOptions']['noVerify']['state'] = $value;

        return $this;
    }
}
