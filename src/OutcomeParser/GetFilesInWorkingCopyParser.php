<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParserInterface;

/**
 * @todo Rename "path" to "filePath". Should be consistent with other parsers.
 */
class GetFilesInWorkingCopyParser implements OutcomeParserInterface
{
    /**
     * Parses the output of "git ls-files" command with -t and -z options.
     *
     * The output format is expected to be:
     * H i/lf    w/lf    attr/                 \t.circleci/config.yml\0
     *
     * Where:
     * - First character is the file status (H, S, M, R, C, K, ?, U).
     * - Followed by attributes like "i/lf", "w/lf", "attr/".
     * - Then a tab character.
     * - Then the file path.
     * - Entries are separated by null bytes (\0).
     *
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

        $pattern = implode(
            '',
            [
                '/^',
                '(?P<statusChar>.?)',
                '(?P<attributes> .*)',
                '\t',
                '(?P<path>.+)',
                '$/',
            ],
        );

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

            $items[$matches['path']] = [
                'status' => FileStatus::tryFrom($matches['statusChar']),
                'statusChar' => $matches['statusChar'],
                'path' => $matches['path'],
                'attributes' => $this->parseAttributes($matches['attributes']),
            ];
        }

        return $items;
    }

    /**
     * @param string $attributes
     *
     * @return array<string, string>
     */
    public function parseAttributes(string $attributes): array
    {
        if ($attributes === '') {
            return [];
        }

        /** @var array<string> $groups */
        $groups = (array) preg_split('@(i|w|attr)/@', trim($attributes), -1, \PREG_SPLIT_DELIM_CAPTURE);
        array_shift($groups);
        $attributes = [];
        for ($i = 0; $i < count($groups); $i += 2) {
            // @todo Parse key-value pairs.
            $attributes[$groups[$i]] = trim($groups[$i + 1]);
        }

        return $attributes;
    }
}
