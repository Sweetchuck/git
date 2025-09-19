<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionAddTrait;

/**
 * Represents the "git remote set-branches" command.
 */
class SetRemoteBranches extends CliCommandBase
{
    use OptionAddTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'set-branches'];
        $this->properties['commandArguments']['name'] = null;
        $this->properties['commandArguments']['branch'] = null;
        $this->initPropertyAdd();

        return $this;
    }

    /**
     * {@inheritdoc}
     *
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyAdd($properties);

        if (array_key_exists('name', $properties)) {
            $this->setRemoteName($properties['name']);
        }

        if (array_key_exists('branch', $properties)) {
            $this->setBranchName($properties['branch']);
        }

        return $this;
    }

    public function setRemoteName(string $name): static
    {
        $this->properties['commandArguments']['name'] = $name;

        return $this;
    }

    public function getRemoteName(): ?string
    {
        return $this->properties['commandArguments']['name'];
    }

    public function setBranchName(string $name): static
    {
        $this->properties['commandArguments']['branch'] = $name;

        return $this;
    }

    public function getBranchName(): ?string
    {
        return $this->properties['commandArguments']['branch'];
    }
}
