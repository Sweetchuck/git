<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

use Symfony\Component\Process\Process;

class ProcessFactory implements ProcessFactoryInterface
{
    /**
     * @var array<string, string>
     */
    protected array $envVars = [];

    /**
     * {@inheritdoc}
     */
    public function getExtraEnvVars(): array
    {
        return $this->envVars;
    }

    /**
     * {@inheritdoc}
     */
    public function setExtraEnvVars(array $envVars): static
    {
        $this->envVars = $envVars;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function updateExtraEnvVars(array $envVars): static
    {
        foreach ($envVars as $name => $value) {
            if ($value === null) {
                unset($this->envVars[$name]);

                continue;
            }

            $this->envVars[$name] = $value;
        }

        return $this;
    }

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
        $extraEnvVars = $this->getExtraEnvVars();
        if ($extraEnvVars) {
            $env = $env ?? [];
            $env += $extraEnvVars;
        }

        return new Process($command, $cwd, $env, $input, $timeout);
    }
}
