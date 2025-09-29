<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\ResetFiles;

#[CoversClass(ResetFiles::class)]
#[Group('command-git-reset')]
class ResetFilesTest extends CommandTestBase
{
    protected function createCommand(): ResetFiles
    {
        return new ResetFiles();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'reset',
                    'feature-42',
                ],
                'properties' => [
                    'refName' => 'feature-42',
                ],
            ],
            'all-in-one-01' => [
                'expected' => [
                    'git',
                    'reset',
                    '--soft',
                    '--merge',
                    '-N',
                    'issue-42',
                ],
                'properties' => [
                    'gently' => true,
                    'merge' => true,
                    'intentToAdd' => true,
                    'refName' => 'issue-42',
                ],
            ],
            'all-in-one-02' => [
                'expected' => [
                    'git',
                    'reset',
                    '--hard',
                    'issue-42',
                ],
                'properties' => [
                    'gently' => false,
                    'refName' => 'issue-42',
                ],
            ],
            'all-in-one-03' => [
                'expected' => [
                    'git',
                    'reset',
                    '--pathspec-from-file=pathspec.txt',
                    '--pathspec-file-nul',
                    'issue-42',
                    '--',
                    'index.php',
                ],
                'properties' => [
                    'pathSpecFromFile' => 'pathspec.txt',
                    'pathSpecFileNul' => true,
                    'refName' => 'issue-42',
                    'paths' => ['index.php'],
                ],
            ],
        ];
    }
}
