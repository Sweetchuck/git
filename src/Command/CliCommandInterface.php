<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\CommandResult;

interface CliCommandInterface
{

    /**
     * @param array<string, mixed> $options
     */
    public function setOptions(array $options): static;

    /**
     * @return array<string>
     */
    public function getCliCommand(): array;

    public function execute(): CommandResult;
}
