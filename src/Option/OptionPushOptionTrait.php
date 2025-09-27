<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPushOptionTrait
{
    protected function initPropertyPushOption(): static
    {
        $this->properties['commandOptions']['pushOption'] = [
            'type' => 'state:string-required:multi',
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPushOption(array $properties): static
    {
        if (array_key_exists('pushOption', $properties)) {
            $this->setPushOption($properties['pushOption']);
        }

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    public function getPushOption(): array
    {
        return $this->properties['commandOptions']['pushOption']['value'];
    }

    /**
     * @param array<string, bool> $value
     */
    public function setPushOption(array $value): static
    {
        $this->properties['commandOptions']['pushOption']['value'] = $value;

        return $this;
    }
}
