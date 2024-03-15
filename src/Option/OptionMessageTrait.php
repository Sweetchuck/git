<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionMessageTrait
{
    protected function initPropertyMessage(): static
    {
        $this->properties['commandOptions']['message'] = [
            'type' => 'value:string-required',
            'short' => '-m',
            'name' => '--message',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyMessage(array $properties): static
    {
        if (array_key_exists('message', $properties)) {
            $this->setMessage($properties['message']);
        }

        return $this;
    }

    public function getMessage(): ?string
    {
        return $this->properties['commandOptions']['message']['value'];
    }

    public function setMessage(?string $value): static
    {
        $this->properties['commandOptions']['message']['value'] = $value;

        return $this;
    }
}
