<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionContextTrait
{
    protected function initPropertyContext(): static
    {
        $this->properties['commandOptions']['context'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];
        $this->properties['commandOptions']['beforeContext'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];
        $this->properties['commandOptions']['afterContext'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyContext(array $properties): static
    {
        if (array_key_exists('context', $properties)) {
            $this->setContextBoth($properties['context']);
        }

        if (array_key_exists('beforeContext', $properties)) {
            $this->setContextBefore($properties['beforeContext']);
        }

        if (array_key_exists('afterContext', $properties)) {
            $this->setContextAfter($properties['afterContext']);
        }

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function alterFinalPropertiesContext(array &$properties): static
    {
        $before = $this->getContextBefore();
        $after = $this->getContextAfter();
        if ($before === $after) {
            $properties['commandOptions']['context']['value'] = $before;
            $properties['commandOptions']['beforeContext']['value'] = null;
            $properties['commandOptions']['afterContext']['value'] = null;
        }

        return $this;
    }

    public function setContextBoth(null|int $value): static
    {
        $this
            ->setContextBefore($value)
            ->setContextAfter($value);

        return $this;
    }

    public function getContextBefore(): ?int
    {
        return $this->properties['commandOptions']['beforeContext']['value'];
    }

    public function setContextBefore(null|int $value): static
    {
        $this->properties['commandOptions']['beforeContext']['value'] = $value;

        return $this;
    }

    public function getContextAfter(): ?int
    {
        return $this->properties['commandOptions']['afterContext']['value'];
    }

    public function setContextAfter(null|int $value): static
    {
        $this->properties['commandOptions']['afterContext']['value'] = $value;

        return $this;
    }
}
