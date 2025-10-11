<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionPatternTypeTrait
{
    protected function initPropertyPatternType(): static
    {
        $this->properties['commandOptions']['patternType'] = [
            'type' => CommandOptionType::ValueNamePattern,
            'pattern' => '--{{ value }}-regexp',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyPatternType(array $properties): static
    {
        if (array_key_exists('patternType', $properties)) {
            $this->setPatternType($properties['patternType']);
        }

        return $this;
    }

    public function getPatternType(): null|string
    {
        return $this->properties['commandOptions']['patternType']['value'];
    }

    public function setPatternType(null|string $value): static
    {
        // @todo PatternType enum.
        assert(
            in_array($value, ['basic', 'extended', 'perl']),
            sprintf('Invalid value for patternType: %s', $value),
        );
        $this->properties['commandOptions']['patternType']['value'] = $value;

        return $this;
    }
}
