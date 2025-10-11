<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionServerOptionTrait
{
    protected function initPropertyServerOption(): static
    {
        $this->properties['commandOptions']['serverOption'] = [
            'type' => CommandOptionType::ValueMultiFalseString,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyServerOption(array $properties): static
    {
        if (array_key_exists('serverOption', $properties)) {
            $this->setServerOption($properties['serverOption']);
        }

        return $this;
    }

    /**
     * @return null|array<false|string>
     */
    public function getServerOption(): null|array
    {
        return $this->properties['commandOptions']['serverOption']['value'];
    }

    /**
     * @param null|array<false|string> $value
     */
    public function setServerOption(null|array $value): static
    {
        $this->properties['commandOptions']['serverOption']['value'] = $value;

        return $this;
    }
}
