<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionNegotiationTipTrait
{
    protected function initPropertyNegotiationTip(): static
    {
        $this->properties['commandOptions']['negotiationTip'] = [
            'type' => 'value:false:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyNegotiationTip(array $properties): static
    {
        if (array_key_exists('negotiationTip', $properties)) {
            $this->setNegotiationTip($properties['negotiationTip']);
        }

        return $this;
    }

    public function getNegotiationTip(): null|false|string
    {
        return $this->properties['commandOptions']['negotiationTip']['value'];
    }

    public function setNegotiationTip(null|false|string $value): static
    {
        $this->properties['commandOptions']['negotiationTip']['value'] = $value;

        return $this;
    }
}
