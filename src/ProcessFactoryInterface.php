<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Symfony\Component\Process\Process;

interface ProcessFactoryInterface
{

    /**
     * @param array<string> $command
     * @param null|array<string> $env
     */
    public function createProcess(
        array $command,
        ?string $cwd = null,
        ?array $env = null,
        mixed $input = null,
        ?float $timeout = 60,
    ): Process;
}
