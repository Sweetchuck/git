<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionCommitterDateIsAuthorDateTrait
{

    protected function initPropertyCommitterDateIsAuthorDate(): static
    {
        $this->properties['commandOptions']['committerDateIsAuthorDate'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyCommitterDateIsAuthorDate(array $properties): static
    {
        if (array_key_exists('committerDateIsAuthorDate', $properties)) {
            $this->setCommitterDateIsAuthorDate($properties['committerDateIsAuthorDate']);
        }

        return $this;
    }

    public function getCommitterDateIsAuthorDate(): ?bool
    {
        return $this->properties['commandOptions']['committerDateIsAuthorDate']['state'];
    }

    public function setCommitterDateIsAuthorDate(?bool $value): static
    {
        $this->properties['commandOptions']['committerDateIsAuthorDate']['state'] = $value;

        return $this;
    }
}
