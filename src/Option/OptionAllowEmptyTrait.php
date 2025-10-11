<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAllowEmptyTrait
{
    protected function initPropertyAllowEmpty(): static
    {
        $this->properties['commandOptions']['allowEmpty'] = [
            'type' => CommandOptionType::StateTrue,
            'name' => '--allow-empty',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAllowEmpty(array $properties): static
    {
        if (array_key_exists('allowEmpty', $properties)) {
            $this->setAllowEmpty($properties['allowEmpty']);
        }

        return $this;
    }

    public function getAllowEmpty(): ?bool
    {
        return $this->properties['commandOptions']['allowEmpty']['state'];
    }

    public function setAllowEmpty(?bool $value): static
    {
        $this->properties['commandOptions']['allowEmpty']['state'] = $value;

        return $this;
    }
}
