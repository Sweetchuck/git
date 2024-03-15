<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Option\OptionDryRunTrait;

/**
 * Represents the "git remote prune" command.
 */
class PruneRemote extends CliCommandBase
{
    use OptionDryRunTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['remote', 'prune'];
        $this->properties['commandArguments'] = [
            'name' => null,
        ];

        $this->initPropertyDryRun();

        return $this;
    }

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static
    {
        parent::setProperties($properties);

        $this->setPropertyDryRun($properties);

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
