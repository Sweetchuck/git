<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git rebase --continue" command.
 */
class ContinueRebase extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['rebase'];
        $this->properties['commandOptions']['continue'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }
}
