<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

/**
 * @phpstan-import-type SweetchuckGitCommandGrepFilesArtifacts from \Sweetchuck\Git\Phpstan
 */
class GrepFilesParser implements OutcomeParserInterface
{

    /**
     * {@inheritdoc}
     *
     * @phpstan-return null|SweetchuckGitCommandGrepFilesArtifacts
     */
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        return match ($options['stdOutputFormat'] ?? 'default') {
            'onlyFilePaths' => $this->parseOnlyFilePaths($exitCode, $stdOutput, $stdError, $options),
            default => $this->parseDefault($exitCode, $stdOutput, $stdError, $options),
        };
    }

    /**
     * @phpstan-return null|SweetchuckGitCommandGrepFilesArtifacts
     */
    protected function parseDefault(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        // @todo Check $exitCode.
        $artifacts = [
            'files' => [],
        ];

        foreach ($this->splitMatches(rtrim($stdOutput, \PHP_EOL)) as $matchText) {
            $this->addMatchText($artifacts, $matchText);
        }

        return $artifacts;
    }

    /**
     * @phpstan-return null|SweetchuckGitCommandGrepFilesArtifacts
     */
    protected function parseOnlyFilePaths(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): ?array {
        // @todo Check $exitCode.
        $stdOutput = rtrim($stdOutput, \PHP_EOL . "\0");
        if ($stdOutput === '') {
            return [
                'filePaths' => [],
            ];
        }

        return [
            'filePaths' => explode("\0", $stdOutput),
        ];
    }

    /**
     * @return array<string>
     */
    public function splitMatches(string $stdOutput): array
    {
        if ($stdOutput === '') {
            return [];
        }

        $matchesSeparator = $this->getMatchesSeparator();
        if (str_contains($stdOutput, $matchesSeparator)) {
            // Matches separator is there only when
            // --before-context or after-context is used
            // AND
            // there are multiple matches.
            return explode($matchesSeparator, $stdOutput);
        }

        return $this->hasContextLine($stdOutput)
            // Only one match with context lines.
            ? [$stdOutput]
            // One or more matches without context lines.
            : explode(\PHP_EOL, $stdOutput);
    }

    /**
     * @param array<string, mixed> $artifacts
     */
    protected function addMatchText(array &$artifacts, string $matchText): static
    {
        $lines = explode(\PHP_EOL, $matchText);
        foreach ($lines as $line) {
            $parts = explode("\0", $line);
            [$filePath, $lineNumber] = $parts;

            $artifacts['files'][$filePath]['lines'][$lineNumber] = (string) end($parts);

            if (count($parts) === 4) {
                $artifacts['files'][$filePath]['matches'][$lineNumber] = (int) $parts[2];
            }
        }

        return $this;
    }

    /**
     * @phpstan-return non-empty-string
     */
    public function getMatchesSeparator(): string
    {
        return \PHP_EOL . '--' . \PHP_EOL;
    }

    public function hasContextLine(string $text): bool
    {
        return preg_match('/(^|\r|\n)[^\0]+\0\d+\0[^\0\r\n]*(\r|\n|$)/', $text) === 1;
    }
}
