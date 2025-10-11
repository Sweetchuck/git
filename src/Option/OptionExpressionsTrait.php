<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExpressionsTrait
{

    protected function initPropertyExpressions(): static
    {
        $this->properties['commandOptions']['expressions'] = [
            'type' => CommandOptionType::ValueExpressions,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyExpressions(array $properties): static
    {
        if (array_key_exists('expressions', $properties)) {
            $this->setExpressions($properties['expressions']);
        }

        return $this;
    }

    /**
     * @return null|array<mixed>
     */
    public function getExpressions(): null|array
    {
        return $this->properties['commandOptions']['expressions']['value'];
    }

    /**
     * @param null|array<mixed> $value
     */
    public function setExpressions(null|array $value): static
    {
        $this->properties['commandOptions']['expressions']['value'] = $value ?: null;

        return $this;
    }
}
