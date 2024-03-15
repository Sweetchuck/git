<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

class InvokeCustom extends CliCommandBase
{

    /**
     * @var array<string>
     */
    protected array $command = [];

    /**
     * @return array<string>
     */
    public function getCliCommand(): array
    {
        return $this->command;
    }

    /**
     * @param array<string> $command
     */
    public function setCommand(array $command): static
    {
        $this->command = $command;

        return $this;
    }
}
