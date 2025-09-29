<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExecRebaseTrait
{
    protected function initPropertyExec(): static
    {
        $this->properties['commandOptions']['exec'] = [
            'type' => 'array<TId, TValue>',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExec(array $properties): static
    {
        if (array_key_exists('exec', $properties)) {
            $this->setExec($properties['exec']);
        }

        return $this;
    }

    /**
     * @return null|array<string, string>
     */
    public function getExec(): ?array
    {
        return $this->properties['commandOptions']['exec']['value'];
    }

    /**
     * @param null|array<string, string> $value
     */
    public function setExec(?array $value): static
    {
        $this->properties['commandOptions']['exec']['value'] = $value;

        return $this;
    }
}
