<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

interface FormatHandlerInterface
{

    /**
     *
     * @param array<string, mixed> $properties
     *
     * @return array<string, mixed>
     */
    public function createMachineReadableFormatDefinition(array $properties): array;

    /**
     * @param array<string, mixed> $definition
     *
     * @return array<mixed>
     */
    public function parseStdOutput(string $stdOutput, array $definition): array;
}
