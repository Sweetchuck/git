<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionTextTrait
{
    protected function initPropertyText(): static
    {
        $this->properties['commandOptions']['text'] = [
            'type' => 'state:true',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyText(array $properties): static
    {
        if (array_key_exists('text', $properties)) {
            $this->setText($properties['text']);
        }

        return $this;
    }

    public function getText(): ?bool
    {
        return $this->properties['commandOptions']['text']['state'];
    }

    public function setText(?bool $value): static
    {
        $this->properties['commandOptions']['text']['state'] = $value;

        return $this;
    }
}
