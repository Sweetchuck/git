<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

/**
 * Parses the output of the "git ls-tree" command.
 *
 * @todo Delete this when FormatParser works.
 */
class GetFilesInTreeParser implements OutcomeParserInterface
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

        if (trim($stdOutput) === '') {
            return [];
        }

        $pattern = '/^(?P<chmod>[^ ]+) (?P<objectType>[^ ]+) (?P<objectId>[^\t]+)\t(?P<filePath>.+)$/';
        $items = [];
        foreach (explode("\0", rtrim($stdOutput, "\0")) as $line) {
            if ($line === '') {
                continue;
            }

            $matches = [];
            preg_match($pattern, $line, $matches);
            if (!$matches) {
                continue;
            }

            $items[$matches['filePath']] = [
                'chmod' => $matches['chmod'],
                'objectType' => $matches['objectType'],
                'objectId' => $matches['objectId'],
                'filePath' => $matches['filePath'],
            ];
        }

        return $items;
    }
}
