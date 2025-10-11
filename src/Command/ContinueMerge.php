<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class ContinueMerge extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['merge'];
        $this->properties['commandOptions']['continue'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }
}
