<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class LinesParser implements OutcomeParserInterface
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
            'key' => 'stdOutput.lines',
        ];

        $lines = preg_split('/[\r\n]+/', trim($stdOutput, "\r\n"));
        if (!is_array($lines) || $lines === ['']) {
            $lines = [];
        }

        return [
            $options['key'] => $lines,
        ];
    }
}
