<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Helper;

use Symfony\Component\Process\Process;

class DummyProcess extends Process
{

    protected int $exitCode = 0;

    protected string $stdOutput = '';

    protected string $stdError = '';

    public function setOutcome(
        int $exitCode = 0,
        string $stdOutput = '',
        string $stdError = '',
    ): static {
        $this->exitCode = $exitCode;
        $this->stdOutput = $stdOutput;
        $this->stdError = $stdError;

        return $this;
    }

    public function getExitCode(): ?int
    {
        return $this->exitCode;
    }

    public function getOutput(): string
    {
        return $this->stdOutput;
    }

    public function getErrorOutput(): string
    {
        return $this->stdError;
    }

    /**
     * @param array<string, string> $env
     */
    public function start(?callable $callback = null, array $env = []): void
    {
        // Do nothing.
    }

    public function wait(?callable $callback = null): int
    {
        return $this->exitCode;
    }
}
