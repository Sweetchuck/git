<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class AbortMerge extends CliCommandBase
{

    protected function initProperties(): static
    {
        parent::initProperties();
        $this->properties['command'] = ['merge'];
        $this->properties['commandOptions']['abort'] = [
            'type' => \Sweetchuck\Git\CommandOptionType::StateTrue,
            'state' => true,
        ];

        return $this;
    }
}
