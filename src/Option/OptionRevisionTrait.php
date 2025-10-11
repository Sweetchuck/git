<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRevisionTrait
{
    protected function initPropertyRevision(): static
    {
        $this->properties['commandOptions']['revision'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRevision(array $properties): static
    {
        if (array_key_exists('revision', $properties)) {
            $this->setRevision($properties['revision']);
        }

        return $this;
    }

    public function getRevision(): null|false|string
    {
        return $this->properties['commandOptions']['revision']['value'];
    }

    public function setRevision(null|false|string $value): static
    {
        $this->properties['commandOptions']['revision']['value'] = $value;

        return $this;
    }
}
