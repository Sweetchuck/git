<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class QuitMerge extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['merge'];
        $this->properties['commandOptions']['quit'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }
}
