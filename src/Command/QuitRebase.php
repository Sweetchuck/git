<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

/**
 * Represents the "git rebase --quit" command.
 */
class QuitRebase extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['rebase'];
        $this->properties['commandOptions']['quit'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }
}
