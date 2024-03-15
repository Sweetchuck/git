<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class InitRepositoryParser implements OutcomeParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        if ($exitCode !== 0) {
            return null;
        }

        $matches = [];
        preg_match(
            '@ (?P<gitDir>(\w:)?[\\\\/].*)$@is',
            rtrim($stdOutput, "\r\n"),
            $matches,
        );

        if (!isset($matches['gitDir'])) {
            return null;
        }

        return [
            'gitDir' => $matches['gitDir'],
        ];
    }
}
