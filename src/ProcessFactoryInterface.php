<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Symfony\Component\Process\Process;

interface ProcessFactoryInterface
{

    /**
     * @return array<string, string>
     */
    public function getExtraEnvVars(): array;

    /**
     * @param array<string, string> $envVars
     */
    public function setExtraEnvVars(array $envVars): static;

    /**
     * @param array<string, ?string> $envVars
     */
    public function updateExtraEnvVars(array $envVars): static;

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
