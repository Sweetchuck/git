<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git rebase --abort" command.
 */
class AbortRebase extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['rebase'];
        $this->properties['commandOptions']['abort'] = [
            'type' => 'state:true',
            'state' => true,
        ];

        return $this;
    }
}
