<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionGentlyTrait
{

    protected function initPropertyGently(): static
    {
        $this->properties['commandOptions']['gently'] = [
            'type' => CommandOptionType::StateBool,
            'name' => '--soft',
            'name-no' => '--hard',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyGently(array $properties): static
    {
        if (array_key_exists('gently', $properties)) {
            $this->setGently($properties['gently']);
        }

        return $this;
    }

    public function getGently(): ?bool
    {
        return $this->properties['commandOptions']['gently']['state'];
    }

    /**
     * @param null|bool $value
     *   False: --hard
     *   True: --soft
     */
    public function setGently(?bool $value): static
    {
        $this->properties['commandOptions']['gently']['state'] = $value;

        return $this;
    }
}
