<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Command;

use Sweetchuck\Git\CommandResult;
use Sweetchuck\Git\ProcessFactoryInterface;

interface CliCommandInterface
{

    // region processFactory
    public function getProcessFactory(): ?ProcessFactoryInterface;

    public function setProcessFactory(?ProcessFactoryInterface $processFactory): static;
    // endregion

    /**
     * @param array<string, mixed> $properties
     */
    public function setProperties(array $properties): static;

    public function getWorkingDirectory(): ?string;

    public function setWorkingDirectory(?string $workingDirectory): static;

    public function getGitDir(): ?string;

    public function setGitDir(?string $gitDir): static;

    public function getWorkTree(): ?string;

    public function setWorkTree(?string $dir): static;

    /**
     * @return ?array<string, string>
     */
    public function getEnvVars(): ?array;

    /**
     * @param ?array<string, string> $envVars
     */
    public function setEnvVars(?array $envVars): static;

    /**
     * @param array<string, ?string> $envVars
     */
    public function updateEnvVars(array $envVars): static;

    public function getCwd(): ?string;

    public function setCwd(?string $dir): static;

    /**
     * @return array<string>
     */
    public function getCliCommand(): array;

    public function execute(): CommandResult;
}
