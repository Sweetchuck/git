<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionSetUpstreamTrait
{

    protected function initPropertySetUpstream(): static
    {
        $this->properties['commandOptions']['setUpstream'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertySetUpstream(array $properties): static
    {
        if (array_key_exists('setUpstream', $properties)) {
            $this->setSetUpstream($properties['setUpstream']);
        }

        return $this;
    }

    public function getSetUpstream(): ?bool
    {
        return $this->properties['commandOptions']['setUpstream']['state'];
    }

    public function setSetUpstream(?bool $value): static
    {
        $this->properties['commandOptions']['setUpstream']['state'] = $value;

        return $this;
    }
}
