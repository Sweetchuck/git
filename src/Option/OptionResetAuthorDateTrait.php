<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionResetAuthorDateTrait
{

    protected function initPropertyResetAuthorDate(): static
    {
        $this->properties['commandOptions']['resetAuthorDate'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyResetAuthorDate(array $properties): static
    {
        if (array_key_exists('resetAuthorDate', $properties)) {
            $this->setResetAuthorDate($properties['resetAuthorDate']);
        }

        return $this;
    }

    public function getResetAuthorDate(): ?bool
    {
        return $this->properties['commandOptions']['resetAuthorDate']['state'];
    }

    public function setResetAuthorDate(?bool $value): static
    {
        $this->properties['commandOptions']['resetAuthorDate']['state'] = $value;

        return $this;
    }
}
