<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionTemplateTrait
{

    protected function initPropertyTemplate(): static
    {
        $this->properties['commandOptions']['template'] = [
            'type' => CommandOptionType::ValueFalseStringRequired,
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyTemplate(array $properties): static
    {
        if (array_key_exists('template', $properties)) {
            $this->setTemplate($properties['template']);
        }

        return $this;
    }

    public function getTemplate(): null|false|string
    {
        return $this->properties['commandOptions']['template']['value'];
    }

    public function setTemplate(null|false|string $value): static
    {
        $this->properties['commandOptions']['template']['value'] = $value;

        return $this;
    }
}
