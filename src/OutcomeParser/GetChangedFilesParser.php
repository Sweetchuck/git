<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\OutcomeParser;

use Sweetchuck\Git\FilePathStyle;
use Sweetchuck\Git\FileStatus;
use Sweetchuck\Git\OutcomeParserInterface;
use Sweetchuck\Git\Struct\ChangedFile;
use Symfony\Component\Filesystem\Path;

class GetChangedFilesParser implements OutcomeParserInterface
{
    public function parse(
        int $exitCode,
        string $stdOutput,
        string $stdError,
        array $options = [],
    ): array {
        $artifacts = [
            'files' => [],
        ];

        $stdOutput = trim($stdOutput, "\0");
        if (!$stdOutput) {
            return $artifacts;
        }

        $parts = explode("\0", $stdOutput);
        for ($i = 0; $i < count($parts); $i += 2) {
            if (!isset($parts[$i + 1])) {
                break;
            }

            $file = new ChangedFile(
                $this->processFileName($parts[$i + 1], $options),
                FileStatus::tryFrom($parts[$i]),
            );
            $artifacts['files'][$file->fileName] = $file;
        }

        return $artifacts;
    }

    /**
     * @param string $fileName
     * @param array<string, mixed> $options
     */
    protected function processFileName(string $fileName, array $options): string
    {
        // @todo Validate $options.
        return match ($options['filePathStyle'] ?? FilePathStyle::RelativeToWorkingDirectory) {
            FilePathStyle::RelativeToTopLevel => $fileName,
            FilePathStyle::RelativeToWorkingDirectory => "./$fileName",
            default => Path::join($options['topLevel'], $fileName),
        };
    }
}
