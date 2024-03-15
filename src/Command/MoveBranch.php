<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git branch --move" command.
 *
 * @see https://git-scm.com/docs/git-branch
 *
 * @phpstan-import-type SweetchuckGitCommandMoveBranchProperties from \Sweetchuck\Git\Phpstan
 */
class MoveBranch extends CliCommandBase
{
    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['branch'];
        $this->properties['commandOptions']['move'] = [
            'type' => 'state:bool',
            'name' => '--move',
            'state' => true,
        ];

        $this->properties['commandArguments'] = [
            'oldName' => null,
            'newName' => null,
        ];

        return $this;
    }

    /**
     * @phpstan-param SweetchuckGitCommandMoveBranchProperties $properties
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
