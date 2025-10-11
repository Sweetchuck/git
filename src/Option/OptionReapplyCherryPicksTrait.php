<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

use Sweetchuck\Git\CommandOptionType;

/**
 * @property array<string, mixed> $properties
 */
trait OptionReapplyCherryPicksTrait
{

    protected function initPropertyReapplyCherryPicks(): static
    {
        $this->properties['commandOptions']['reapplyCherryPicks'] = [
            'type' => CommandOptionType::StateBool,
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyReapplyCherryPicks(array $properties): static
    {
        if (array_key_exists('reapplyCherryPicks', $properties)) {
            $this->setReapplyCherryPicks($properties['reapplyCherryPicks']);
        }

        return $this;
    }

    public function getReapplyCherryPicks(): ?bool
    {
        return $this->properties['commandOptions']['reapplyCherryPicks']['state'];
    }

    public function setReapplyCherryPicks(?bool $value): static
    {
        $this->properties['commandOptions']['reapplyCherryPicks']['state'] = $value;

        return $this;
    }
}
