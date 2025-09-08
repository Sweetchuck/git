<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\RemoveFiles;

#[CoversClass(RemoveFiles::class)]
#[Group('command-git-rm')]
class RemoveFilesTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        return new RemoveFiles();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'rm',
                ],
                'properties' => [],
            ],
            'single-file' => [
                'expected' => [
                    'git',
                    'rm',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                ],
            ],
            'multiple-files' => [
                'expected' => [
                    'git',
                    'rm',
                    '--',
                    'file1.txt',
                    'file2.txt',
                ],
                'properties' => [
                    'paths' => ['file1.txt', 'file2.txt'],
                ],
            ],
            'paths-with-bool-true' => [
                'expected' => [
                    'git',
                    'rm',
                    '--',
                    'file1.txt',
                    'file2.txt',
                ],
                'properties' => [
                    'paths' => [
                        'file1.txt' => true,
                        'file2.txt' => true,
                        'file3.txt' => false,
                    ],
                ],
            ],
            'force' => [
                'expected' => [
                    'git',
                    'rm',
                    '--force',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'force' => true,
                    'paths' => ['file.txt'],
                ],
            ],
            'dry-run' => [
                'expected' => [
                    'git',
                    'rm',
                    '--dry-run',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'dryRun' => true,
                    'paths' => ['file.txt'],
                ],
            ],
            'cached' => [
                'expected' => [
                    'git',
                    'rm',
                    '--cached',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'cached' => true,
                    'paths' => ['file.txt'],
                ],
            ],
            'recursive' => [
                'expected' => [
                    'git',
                    'rm',
                    '-r',
                    '--',
                    'directory/',
                ],
                'properties' => [
                    'recursive' => true,
                    'paths' => ['directory/'],
                ],
            ],
            'ignore-missing' => [
                'expected' => [
                    'git',
                    'rm',
                    '--ignore-missing',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'ignoreMissing' => true,
                    'paths' => ['file.txt'],
                ],
            ],
            'ignore-unmatch' => [
                'expected' => [
                    'git',
                    'rm',
                    '--ignore-unmatch',
                    '--',
                    'file-*.txt',
                ],
                'properties' => [
                    'ignoreUnmatch' => true,
                    'paths' => ['file-*.txt'],
                ],
            ],
            'sparse' => [
                'expected' => [
                    'git',
                    'rm',
                    '--sparse',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'sparse' => true,
                    'paths' => ['file.txt'],
                ],
            ],
            'multiple-flags' => [
                'expected' => [
                    'git',
                    'rm',
                    '--force',
                    '--dry-run',
                    '--cached',
                    '-r',
                    '--ignore-missing',
                    '--ignore-unmatch',
                    '--sparse',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'force' => true,
                    'dryRun' => true,
                    'cached' => true,
                    'recursive' => true,
                    'ignoreMissing' => true,
                    'ignoreUnmatch' => true,
                    'sparse' => true,
                    'paths' => ['file.txt'],
                ],
            ],
            'with-git-dir' => [
                'expected' => [
                    'git',
                    '--git-dir=/a/b/.git',
                    'rm',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'gitDir' => '/a/b/.git',
                    'paths' => ['file.txt'],
                ],
            ],
        ];
    }
}
