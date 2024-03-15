<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Symfony\Component\Process\Process;

class ProcessFactory implements ProcessFactoryInterface
{

    /**
     * {@inheritdoc}
     */
    public function createProcess(
        array $command,
        ?string $cwd = null,
        ?array $env = null,
        mixed $input = null,
        ?float $timeout = 60,
    ): Process {
        return new Process($command, $cwd, $env, $input, $timeout);
    }
}
