<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionPruneTrait;

/**
 * Represents the "git remote update" command.
 */
class UpdateRemote extends CliCommandBase
{
    use OptionPruneTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'update'];

        $this->initPropertyPrune();

        $this->properties['commandArguments'] = [
            'name' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        $this->setPropertyPrune($properties);

        if (array_key_exists('name', $properties)) {
            $this->setName($properties['name']);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setName(?string $value): static
    {
        $this->properties['commandArguments']['name'] = $value;

        return $this;
    }
}
