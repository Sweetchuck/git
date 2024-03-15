<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git remote rename" command.
 */
class RenameRemote extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'rename'];

        $this->properties['commandOptions']['progress'] = [
            'type' => 'state:bool',
            'state' => false,
        ];

        $this->properties['commandArguments'] = [
            'oldName' => null,
            'newName' => null,
        ];

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        if (array_key_exists('oldName', $properties)) {
            $this->setOldName($properties['oldName']);
        }

        if (array_key_exists('newName', $properties)) {
            $this->setNewName($properties['newName']);
        }

        return $this;
    }

    public function getOldName(): ?string
    {
        return $this->properties['commandArguments']['oldName'];
    }

    public function setOldName(string $value): static
    {
        $this->properties['commandArguments']['oldName'] = $value;

        return $this;
    }

    public function getNewName(): ?string
    {
        return $this->properties['commandArguments']['newName'];
    }

    public function setNewName(string $value): static
    {
        $this->properties['commandArguments']['newName'] = $value;

        return $this;
    }
}
