<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Symfony\Component\Process\Process;

class CommandResult
{
    public Process $process;

    /**
     * @var array<string, mixed>
     */
    public ?array $artifacts = null;
}
