<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class SkipRebase extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['rebase'];
        $this->properties['commandOptions']['skip'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }
}
