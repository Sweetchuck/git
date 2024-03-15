<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\StageFiles;

#[CoversClass(StageFiles::class)]
#[Group('command-git-add')]
class StageFilesTest extends CommandTestBase
{
    protected function createCommand(): StageFiles
    {
        return new StageFiles();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'add'],
                'properties' => [],
            ],
            'patch' => [
                'expected' => ['git', 'add', '--patch'],
                'properties' => [
                    'patch' => true,
                ],
            ],
            'intent-to-add + pathspec' => [
                'expected' => ['git', 'add', '--intent-to-add', '--', 'README.md'],
                'properties' => [
                    'intentToAdd' => true,
                    'paths' => ['README.md'],
                ],
            ],
            'chmod +x' => [
                'expected' => ['git', 'add', '--chmod=+x', '--', 'script.sh'],
                'properties' => [
                    'chmod' => '+x',
                    'paths' => ['script.sh'],
                ],
            ],
            'pathspec-from-file (stdin) + -z' => [
                'expected' => ['git', 'add', '--pathspec-from-file=-', '--pathspec-file-nul'],
                'properties' => [
                    'pathSpecFromFile' => '-',
                    'pathSpecFileNul' => true,
                ],
            ],
            'multiple flags' => [
                'expected' => [
                    'git',
                    'add',
                    '--edit',
                    '--update',
                    '--ignore-removal',
                    '--renormalize',
                    '--refresh',
                    '--ignore-errors',
                    '--ignore-missing',
                    '--no-warn-embedded-repo',
                ],
                'properties' => [
                    'edit' => true,
                    'update' => true,
                    'ignoreRemoval' => true,
                    'renormalize' => true,
                    'refresh' => true,
                    'ignoreErrors' => true,
                    'ignoreMissing' => true,
                    'noWarnEmbeddedRepo' => true,
                ],
            ],
        ];
    }
}
