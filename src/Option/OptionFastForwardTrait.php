<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\FastForward;

/**
 * @property array<string, mixed> $properties
 */
trait OptionFastForwardTrait
{
    protected function initPropertyFastForward(): static
    {
        $this->properties['commandOptions']['fastForward'] = [
            'type' => 'value:name-mapping',
            'mapping' => [
                'no' => '--no-ff',
                'yes' => '--ff',
                'only' => '--ff-only',
            ],
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyFastForward(array $properties): static
    {
        if (array_key_exists('fastForward', $properties)) {
            $this->setFastForward($properties['fastForward']);
        }

        return $this;
    }

    public function getFastForward(): null|string
    {
        return $this->properties['commandOptions']['fastForward']['value'];
    }

    public function setFastForward(null|bool|string $value): static
    {
        if ($value === true) {
            $value = 'yes';
        } elseif ($value === false) {
            $value = 'no';
        }

        $this->properties['commandOptions']['fastForward']['value'] = $value;

        return $this;
    }
}
