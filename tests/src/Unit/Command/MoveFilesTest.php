<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\MoveFiles;

#[CoversClass(MoveFiles::class)]
#[Group('command-git-mv')]
class MoveFilesTest extends CommandTestBase
{
    protected function createCommand(): MoveFiles
    {
        return new MoveFiles();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'mv'],
                'properties' => [],
            ],
            'with paths' => [
                'expected' => ['git', 'mv', '--', 'old.txt', 'new.txt'],
                'properties' => [
                    'paths' => ['old.txt', 'new.txt'],
                ],
            ],
            'force' => [
                'expected' => ['git', 'mv', '--force'],
                'properties' => [
                    'force' => true,
                ],
            ],
            'dry-run' => [
                'expected' => ['git', 'mv', '--dry-run'],
                'properties' => [
                    'dryRun' => true,
                ],
            ],
            'skipErrors' => [
                'expected' => ['git', 'mv', '-k'],
                'properties' => [
                    'skipErrors' => true,
                ],
            ],
            'multiple options with paths' => [
                'expected' => ['git', 'mv', '--force', '--dry-run', '-k', '--', 'src/', 'dest/'],
                'properties' => [
                    'force' => true,
                    'dryRun' => true,
                    'skipErrors' => true,
                    'paths' => ['src/', 'dest/'],
                ],
            ],
            'multiple sources to directory' => [
                'expected' => ['git', 'mv', '--', 'file1.txt', 'file2.txt', 'target-dir/'],
                'properties' => [
                    'paths' => ['file1.txt', 'file2.txt', 'target-dir/'],
                ],
            ],
        ];
    }
}
