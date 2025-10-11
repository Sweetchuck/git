<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git symbolic-ref --delete <name>" command.
 */
class DeleteSymbolicRef extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['symbolic-ref'];
        $this->properties['commandOptions']['delete'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateBool,
            'state' => true,
        ];

        $this->properties['commandArguments']['name'] = null;

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('name', $properties)) {
            $this->setName($properties['name']);
        }

        return $this;
    }

    public function getName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setName(string $name): static
    {
        $this->properties['commandArguments']['name'] = $name;

        return $this;
    }
}
