<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExcludeTrait
{

    protected function initPropertyExclude(): static
    {
        $this->properties['commandOptions']['exclude'] = [
            'type' => 'value:string-multiple',
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExclude(array $properties): static
    {
        if (array_key_exists('exclude', $properties)) {
            $this->setExclude($properties['exclude']);
        }

        return $this;
    }

    /**
     * @return array<string, bool>
     */
    public function getExclude(): array
    {
        return $this->properties['commandOptions']['exclude']['value'];
    }

    /**
     * @param array<string, bool> $value
     */
    public function setExclude(array $value): static
    {
        $this->properties['commandOptions']['exclude']['value'] = $value;

        return $this;
    }
}
