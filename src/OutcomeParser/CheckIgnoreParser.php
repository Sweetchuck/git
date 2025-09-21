<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\OutcomeParserInterface;

class CheckIgnoreParser implements OutcomeParserInterface
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
        if ($exitCode > 1) {
            return null;
        }

        $lines = preg_split('/[\r\n]+/', trim($stdOutput, "\r\n"));
        if (!is_array($lines) || $lines === ['']) {
            $lines = [];
        }

        $artifacts = [
            'matching' => [],
            'nonMatching' => [],
        ];
        foreach ($lines as $line) {
            $this->parseLine($artifacts, $options, $line);
        }

        return $artifacts;
    }

    /**
     * @param array<string, mixed> $artifacts
     * @param array<string, mixed> $options
     */
    protected function parseLine(array &$artifacts, array $options, string $line): static
    {
        [$rule, $filePath] = explode("\t", $line, 2);
        if ($rule === '::') {
            $artifacts['nonMatching'][] = $filePath;

            return $this;
        }

        [$ignoreFilePath, $lineNumber, $pattern] = explode(':', $rule, 3);
        $artifacts['matching'][$filePath][] = [
            'filePath' => $ignoreFilePath,
            'lineNumber' => (int) $lineNumber,
            'pattern' => $pattern,
        ];

        return $this;
    }
}
