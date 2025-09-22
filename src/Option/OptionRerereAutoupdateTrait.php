<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionRerereAutoupdateTrait
{
    protected function initPropertyRerereAutoupdate(): static
    {
        $this->properties['commandOptions']['rerereAutoupdate'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyRerereAutoupdate(array $properties): static
    {
        if (array_key_exists('rerereAutoupdate', $properties)) {
            $this->setRerereAutoupdate($properties['rerereAutoupdate']);
        }

        return $this;
    }

    public function getRerereAutoupdate(): ?bool
    {
        return $this->properties['commandOptions']['rerereAutoupdate']['state'];
    }

    public function setRerereAutoupdate(?bool $value): static
    {
        $this->properties['commandOptions']['rerereAutoupdate']['state'] = $value;

        return $this;
    }
}
