<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentNamesTrait;

/**
 * Represents the "git branch --delete" command.
 */
class DeleteBranch extends CliCommandBase
{
    use ArgumentNamesTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['branch'];
        $this->properties['commandOptions']['delete'] = [
            'type' => 'state:bool',
            'name' => '--delete',
            'state' => true,
        ];

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

        if (array_key_exists('force', $properties)) {
            $this->setForce($properties['force']);
        }

        if (array_key_exists('names', $properties)) {
            $this->setNames($properties['names']);
        }

        return $this;
    }

    public function getForce(): bool
    {
        return $this->properties['commandOptions']['delete']['name'] === '-D';
    }

    public function setForce(bool $value): static
    {
        $this->properties['commandOptions']['delete']['name'] = $value ? '-D' : '--delete';

        return $this;
    }
}
