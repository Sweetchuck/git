<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionWriteFetchHeadTrait
{

    protected function initPropertyWriteFetchHead(): static
    {
        $this->properties['commandOptions']['writeFetchHead'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyWriteFetchHead(array $properties): static
    {
        if (array_key_exists('writeFetchHead', $properties)) {
            $this->setWriteFetchHead($properties['writeFetchHead']);
        }

        return $this;
    }

    public function getWriteFetchHead(): ?bool
    {
        return $this->properties['commandOptions']['writeFetchHead']['state'];
    }

    public function setWriteFetchHead(?bool $value): static
    {
        $this->properties['commandOptions']['writeFetchHead']['state'] = $value;

        return $this;
    }
}
