<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionBundleUriTrait
{
    protected function initPropertyBundleUri(): static
    {
        $this->properties['commandOptions']['bundleUri'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyBundleUri(array $properties): static
    {
        if (array_key_exists('bundleUri', $properties)) {
            $this->setBundleUri($properties['bundleUri']);
        }

        return $this;
    }

    public function getBundleUri(): ?string
    {
        return $this->properties['commandOptions']['bundleUri']['value'];
    }

    public function setBundleUri(?string $value): static
    {
        $this->properties['commandOptions']['bundleUri']['value'] = $value;

        return $this;
    }
}
