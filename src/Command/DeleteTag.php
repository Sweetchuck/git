<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\Argument\ArgumentNamesTrait;

/**
 * Represents the "git tag --delete" command.
 */
class DeleteTag extends CliCommandBase
{
    use ArgumentNamesTrait;

    protected function initProperties(): static
    {
        parent::initProperties();

        $this->properties['command'] = ['tag'];
        $this->properties['commandOptions']['delete'] = [
            'type' => 'state:bool',
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

        if (array_key_exists('names', $properties)) {
            $this->setNames($properties['names']);
        }

        return $this;
    }
}
