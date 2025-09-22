<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionGpgSignTrait
{
    protected function initPropertyGpgSign(): static
    {
        $this->properties['commandOptions']['gpgSign'] = [
            'type' => 'value:true-false:string',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyGpgSign(array $properties): static
    {
        if (array_key_exists('gpgSign', $properties)) {
            $this->setGpgSign($properties['gpgSign']);
        }

        return $this;
    }

    public function getGpgSign(): null|bool|string
    {
        return $this->properties['commandOptions']['gpgSign']['value'];
    }

    public function setGpgSign(null|bool|string $value): static
    {
        $this->properties['commandOptions']['gpgSign']['value'] = $value;

        return $this;
    }
}
