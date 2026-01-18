<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\FetchResult;
use Sweetchuck\Git\OutcomeParserInterface;

class FetchRefsParser implements OutcomeParserInterface
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
        if ($exitCode !== 0 && $exitCode !== 1) {
            return null;
        }

        $items = [];
        $lines = preg_split('/[\r\n]+/', trim($stdOutput, "\r\n"));
        if (!is_array($lines) || $lines === ['']) {
            $lines = [];
        }

        $pattern = '/^(?P<result>.) (?P<local>[^ ]+) (?P<remote>[^ ]+) (?P<ref>.+)$/';
        foreach ($lines as $line) {
            $matches = [];
            preg_match($pattern, $line, $matches);
            if (!$matches) {
                // @todo Error handling.
                continue;
            }

            $items[$matches['ref']] = [
                'result' => FetchResult::tryFrom($matches['result']),
                'local' => $matches['local'],
                'remote' => $matches['remote'],
                'ref' => $matches['ref'],
            ];
        }

        return $items;
    }
}
