<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property \Sweetchuck\Git\Utils $utils
 * @property array<string, mixed> $properties
 */
trait OptionDiffFilterTrait
{
    protected function initPropertyDiffFilter(): static
    {
        $this->properties['commandOptions']['diffFilter'] = [
            'type' => CommandOptionType::ValueStringRequired,
            'value' => '',
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyDiffFilter(array $properties): static
    {
        if (array_key_exists('diffFilter', $properties)) {
            $this->setDiffFilter($properties['diffFilter']);
        }

        return $this;
    }

    /**
     * @return array<string, ?bool>
     */
    public function getDiffFilter(): array
    {
        return $this->utils->explodeDiffFilter($this->properties['commandOptions']['diffFilter']['value']);
    }

    /**
     * @param array<string, ?bool> $value
     */
    public function setDiffFilter(array $value): static
    {
        $this->properties['commandOptions']['diffFilter']['value'] = $this->utils->implodeDiffFilter($value);

        return $this;
    }
}
