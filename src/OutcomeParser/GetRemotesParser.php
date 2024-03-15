<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class GetRemotesParser implements OutcomeParserInterface
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

        $items = [];
        $pattern = '/^(?P<name>[^\s]+)\s+(?P<url>.+) \((?P<type>.+)\)$/';
        foreach (preg_split('/\s*?\n\s*/', trim($stdOutput)) ?: [] as $line) {
            $matches = [];
            if (preg_match($pattern, $line, $matches) !== 1) {
                continue;
            }

            $items[$matches['name']][$matches['type']] = $matches['url'];
        }

        return $items;
    }
}
