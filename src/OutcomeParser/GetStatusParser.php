<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class GetStatusParser implements OutcomeParserInterface
{
    /**
     * {@inheritdoc}
     */
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): array {
        if ($exitCode || !trim($stdOutput)) {
            return [];
        }

        $items = [];
        foreach (explode("\0", rtrim($stdOutput, "\0")) as $line) {
            $matches = [];
            preg_match('/^(?P<status>.{2}) (?P<fileName>.+)/', $line, $matches);
            if (isset($matches['status'], $matches['fileName'])) {
                // @todo Status should be divided into Staged and WorkingCopy.
                $items[$matches['fileName']] = $matches['status'];
            }
        }

        return $items;
    }
}
