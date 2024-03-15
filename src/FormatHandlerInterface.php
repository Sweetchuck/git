<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

interface FormatHandlerInterface
{

    public function getUniqueIdGenerator(): ?callable;

    public function setUniqueIdGenerator(?callable $uniqueIdGenerator): static;

    /**
     * @param array<string, mixed> $refPropertyMapping
     *
     * @return array<string, mixed>
     */
    public function createMachineReadableFormatDefinition(?array $refPropertyMapping): array;
}
