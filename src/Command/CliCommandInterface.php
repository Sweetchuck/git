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

    public function getCwd(): ?string;

    public function setCwd(?string $dir): static;

    /**
     * @return array<string>
     */
    public function getCliCommand(): array;

    public function execute(): CommandResult;
}
