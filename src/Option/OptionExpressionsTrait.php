<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionExpressionsTrait
{

    protected function initPropertyExpressions(): static
    {
        $this->properties['commandOptions']['expressions'] = [
            'type' => 'value:expressions',
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

    public function getExpressions(): null|array
    {
        return $this->properties['commandOptions']['expressions']['value'];
    }

    public function setExpressions(null|array $value): static
    {
        $this->properties['commandOptions']['expressions']['value'] = $value ?: null;

        return $this;
    }
}
