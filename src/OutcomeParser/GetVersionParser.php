<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class GetVersionParser implements OutcomeParserInterface
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
        preg_match('/^git version (?P<version>\S+)/', $stdOutput, $matches);

        return isset($matches['version'])
            ? ['version' => $matches['version']]
            : null;
    }
}
