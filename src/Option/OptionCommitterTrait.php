<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionCommitterTrait
{
    protected function initPropertyCommitter(): static
    {
        $this->properties['commandOptions']['committer'] = [
            'type' => 'value:string-required',
            'value' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyCommitter(array $properties): static
    {
        if (array_key_exists('committer', $properties)) {
            $this->setCommitter($properties['committer']);
        }

        return $this;
    }

    public function getCommitter(): ?string
    {
        return $this->properties['commandOptions']['committer']['value'];
    }

    public function setCommitter(?string $value): static
    {
        $this->properties['commandOptions']['committer']['value'] = $value;

        return $this;
    }
}
