<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionTrackTrait
{
    protected function initPropertyTrack(): static
    {
        $this->properties['commandOptions']['track'] = [
            'type' => CommandOptionType::ValueMultiFalseString,
            'value' => [],
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyTrack(array $properties): static
    {
        if (array_key_exists('track', $properties)) {
            $this->setTrack($properties['track']);
        }

        return $this;
    }

    /**
     * @return null|array<false|string>
     */
    public function getTrack(): null|array
    {
        return $this->properties['commandOptions']['track']['value'];
    }

    /**
     * @param null|array<false|string> $value
     */
    public function setTrack(null|array $value): static
    {
        $this->properties['commandOptions']['track']['value'] = $value;

        return $this;
    }
}
