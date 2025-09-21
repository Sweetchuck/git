<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionMessageTrait;

/**
 * Represents the "git symbolic-ref <name> <pointsTo>" command.
 */
class UpsertSymbolicRef extends CliCommandBase
{
    use OptionMessageTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['symbolic-ref'];
        $this->properties['commandArguments']['name'] = null;
        $this->properties['commandArguments']['pointsTo'] = null;

        $this->initPropertyMessage();
        $this->properties['commandOptions']['message']['name'] = '-m';

        return $this;
    }

    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);
        $this->setPropertyMessage($properties);

        if (array_key_exists('name', $properties)) {
            $this->setName($properties['name']);
        }

        if (array_key_exists('pointsTo', $properties)) {
            $this->setPointsTo($properties['pointsTo']);
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

    public function getPointsTo(): ?string
    {
        return $this->properties['commandArguments']['pointsTo'];
    }

    public function setPointsTo(string $ref): static
    {
        $this->properties['commandArguments']['pointsTo'] = $ref;

        return $this;
    }
}
