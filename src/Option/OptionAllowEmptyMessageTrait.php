<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Option;

/**
 * @property array<string, mixed> $properties
 */
trait OptionAllowEmptyMessageTrait
{

    protected function initPropertyAllowEmptyMessage(): static
    {
        $this->properties['commandOptions']['allowEmptyMessage'] = [
            'type' => 'state:bool',
            'state' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    protected function setPropertyAllowEmptyMessage(array $properties): static
    {
        if (array_key_exists('allowEmptyMessage', $properties)) {
            $this->setAllowEmptyMessage($properties['allowEmptyMessage']);
        }

        return $this;
    }

    public function getAllowEmptyMessage(): ?bool
    {
        return $this->properties['commandOptions']['allowEmptyMessage']['state'];
    }

    public function setAllowEmptyMessage(?bool $value): static
    {
        $this->properties['commandOptions']['allowEmptyMessage']['state'] = $value;

        return $this;
    }
}
