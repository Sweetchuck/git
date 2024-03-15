<?php

declare(strict_types = 1);

namespace Sweetchuck\Git;

interface OutcomeParserInterface
{

    /**
     * @param array<string, mixed> $options
     *
     * @return array<string, mixed>
     */
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array;
}
