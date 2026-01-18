<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\PushResult;

class PushRefsParser implements OutcomeParserInterface
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
        if ($exitCode) {
            return null;
        }

        $items = [];
        $lines = preg_split('/[\r\n]+/', trim($stdOutput, "\r\n"));
        if (!is_array($lines) || $lines === ['']) {
            $lines = [];
        }

        $pattern = '/^(?P<result>.)\t(?P<refNameLocal>[^:]+):(?P<refNameRemote>[^\t]+)\t(?P<message>.+)$/';
        foreach ($lines as $line) {
            $matches = [];
            preg_match($pattern, $line, $matches);
            if (!$matches) {
                // @todo Error handling.
                continue;
            }

            $id = sprintf(
                '%s:%s',
                $matches['refNameLocal'],
                $matches['refNameRemote'],
            );
            $items[$id] = [
                'result' => PushResult::tryFrom($matches['result']),
                'refNameLocal' => $matches['refNameLocal'],
                'refNameRemote' => $matches['refNameRemote'],
                'message' => trim($matches['message'], '[]'),
            ];
        }

        return $items;
    }
}
