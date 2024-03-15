<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Parser that returns the raw stdout and stderr output.
 *
 * This parser simply returns the standard output and standard error as they are,
 * without any processing. The array keys used for these values can be configured.
 */
class RawOutputParser implements OutcomeParserInterface
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
        $options += [
            'stdOutputKey' => 'stdOutput',
            'stdErrorKey' => 'stdError',
        ];

        $artifacts = [];
        if ($options['stdOutputKey'] !== null) {
            $artifacts[$options['stdOutputKey']] = $stdOutput;
        }

        if ($options['stdErrorKey'] !== null) {
            $artifacts[$options['stdErrorKey']] = $stdError;
        }

        return $artifacts;
    }
}
