<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAuthorTrait
{
    protected function initPropertyAuthor(): static
    {
        $this->properties['commandOptions']['author'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAuthor(array $properties): static
    {
        if (array_key_exists('author', $properties)) {
            $this->setAuthor($properties['author']);
        }

        return $this;
    }

    public function getAuthor(): ?string
    {
        return $this->properties['commandOptions']['author']['value'];
    }

    public function setAuthor(?string $value): static
    {
        $this->properties['commandOptions']['author']['value'] = $value;

        return $this;
    }
}
