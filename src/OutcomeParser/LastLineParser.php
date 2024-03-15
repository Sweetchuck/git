<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class LastLineParser implements OutcomeParserInterface
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
            'key' => 'stdOutput.lastLine',
        ];

        $lines = preg_split('/[\r\n]+/', trim($stdOutput, "\r\n"));
        if (!is_array($lines)) {
            return null;
        }

        $lastLine = end($lines);
        if ($lastLine === '' || $lastLine === false) {
            return null;
        }

        return [
            $options['key'] => $lastLine,
        ];
    }
}
