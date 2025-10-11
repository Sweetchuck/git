<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionIpvTrait
{

    protected function initPropertyIpv(): static
    {
        $this->properties['commandOptions']['ipv'] = [
            'type' => CommandOptionType::StateNameSuffix,
            'state' => true,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyIpv(array $properties): static
    {
        if (array_key_exists('ipv', $properties)) {
            $this->setIpv($properties['ipv']);
        }

        return $this;
    }

    public function getIpv(): null|string
    {
        return $this->properties['commandOptions']['ipv']['value'];
    }

    public function setIpv(null|string $value): static
    {
        if ($value !== null
            && !in_array($value, ['4', '6'], true)
        ) {
            throw new \InvalidArgumentException('Invalid ipv value');
        }

        $this->properties['commandOptions']['ipv']['value'] = $value;

        return $this;
    }
}
