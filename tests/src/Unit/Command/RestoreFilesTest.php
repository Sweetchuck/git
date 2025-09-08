<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\RestoreFiles;

#[CoversClass(RestoreFiles::class)]
#[Group('command-git-restore')]
class RestoreFilesTest extends CommandTestBase
{
    protected function createCommand(): RestoreFiles
    {
        return new RestoreFiles();
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
                    'restore',
                    '--',
                    'index.php',
                ],
                'properties' => [
                    'paths' => ['index.php'],
                ],
            ],

            'no paths' => [
                'expected' => [
                    'git',
                    'restore',
                ],
                'properties' => [],
            ],

            'global option - gitDir' => [
                'expected' => [
                    'git',
                    '--git-dir=/repo/.git',
                    'restore',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'gitDir' => '/repo/.git',
                    'paths' => ['file.txt'],
                ],
            ],

            'global option - workTree' => [
                'expected' => [
                    'git',
                    '--work-tree=/repo',
                    'restore',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'workTree' => '/repo',
                    'paths' => ['file.txt'],
                ],
            ],

            'global option - cwd' => [
                'expected' => [
                    'git',
                    '-C',
                    '/working/dir',
                    'restore',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'cwd' => '/working/dir',
                    'paths' => ['file.txt'],
                ],
            ],

            'source option' => [
                'expected' => [
                    'git',
                    'restore',
                    '--source=main',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'source' => 'main',
                    'paths' => ['file.txt'],
                ],
            ],

            'staged = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--staged',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'staged' => true,
                    'paths' => ['file.txt'],
                ],
            ],

            'staged = false' => [
                'expected' => [
                    'git',
                    'restore',
                    '--no-staged',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'staged' => false,
                    'paths' => ['file.txt'],
                ],
            ],

            'workingTree = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--worktree',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'workingTree' => true,
                    'paths' => ['file.txt'],
                ],
            ],

            'workingTree = false' => [
                'expected' => [
                    'git',
                    'restore',
                    '--no-worktree',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'workingTree' => false,
                    'paths' => ['file.txt'],
                ],
            ],

            'ignoreUnmerged = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--ignore-unmerged',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'ignoreUnmerged' => true,
                    'paths' => ['file.txt'],
                ],
            ],

            'overlay = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--overlay',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'overlay' => true,
                    'paths' => ['file.txt'],
                ],
            ],

            'overlay = false' => [
                'expected' => [
                    'git',
                    'restore',
                    '--no-overlay',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'overlay' => false,
                    'paths' => ['file.txt'],
                ],
            ],

            'recurseSubmodules = array with true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--recurse-submodules',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'recurseSubmodules' => [true],
                    'paths' => ['file.txt'],
                ],
            ],

            'recurseSubmodules = array with false' => [
                'expected' => [
                    'git',
                    'restore',
                    '--no-recurse-submodules',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'recurseSubmodules' => [false],
                    'paths' => ['file.txt'],
                ],
            ],

            'merge = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--merge',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'merge' => true,
                    'paths' => ['file.txt'],
                ],
            ],

            'merge = false' => [
                'expected' => [
                    'git',
                    'restore',
                    '--no-merge',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'merge' => false,
                    'paths' => ['file.txt'],
                ],
            ],

            'conflict = diff3' => [
                'expected' => [
                    'git',
                    'restore',
                    '--conflict=diff3',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'conflict' => 'diff3',
                    'paths' => ['file.txt'],
                ],
            ],

            'conflict = merge' => [
                'expected' => [
                    'git',
                    'restore',
                    '--conflict=merge',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'conflict' => 'merge',
                    'paths' => ['file.txt'],
                ],
            ],

            'unified = 3' => [
                'expected' => [
                    'git',
                    'restore',
                    '--unified=3',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'unified' => 3,
                    'paths' => ['file.txt'],
                ],
            ],

            'ignoreSkipWorktreeBits = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--ignore-skip-worktree-bits',
                    '--',
                    'file.txt',
                ],
                'properties' => [
                    'ignoreSkipWorktreeBits' => true,
                    'paths' => ['file.txt'],
                ],
            ],

            'pathSpecFromFile' => [
                'expected' => [
                    'git',
                    'restore',
                    '--pathspec-from-file=paths.txt',
                ],
                'properties' => [
                    'pathSpecFromFile' => 'paths.txt',
                ],
            ],

            'pathSpecFromFile stdin' => [
                'expected' => [
                    'git',
                    'restore',
                    '--pathspec-from-file=-',
                ],
                'properties' => [
                    'pathSpecFromFile' => '-',
                ],
            ],

            'pathSpecFileNul = true' => [
                'expected' => [
                    'git',
                    'restore',
                    '--pathspec-from-file=paths.txt',
                    '--pathspec-file-nul',
                ],
                'properties' => [
                    'pathSpecFileNul' => true,
                    'pathSpecFromFile' => 'paths.txt',
                ],
            ],

            'multiple paths' => [
                'expected' => [
                    'git',
                    'restore',
                    '--',
                    'file1.txt',
                    'dir/file2.php',
                    'src/',
                ],
                'properties' => [
                    'paths' => ['file1.txt', 'dir/file2.php', 'src/'],
                ],
            ],

            'paths with boolean values' => [
                'expected' => [
                    'git',
                    'restore',
                    '--',
                    'include.txt',
                    'another.php',
                ],
                'properties' => [
                    'paths' => [
                        'include.txt' => true,
                        'exclude.txt' => false,
                        'another.php' => true,
                    ],
                ],
            ],

            'combined options' => [
                'expected' => [
                    'git',
                    'restore',
                    '--source=issue-42',
                    '--staged',
                    '--merge',
                    '--conflict=diff3',
                    '--',
                    'src/',
                    'tests/',
                ],
                'properties' => [
                    'source' => 'issue-42',
                    'staged' => true,
                    'merge' => true,
                    'conflict' => 'diff3',
                    'paths' => ['src/', 'tests/'],
                ],
            ],

            'all options combined' => [
                'expected' => [
                    'git',
                    '--git-dir=/repo/.git',
                    '--work-tree=/repo',
                    '-C',
                    '/working/dir',
                    'restore',
                    '--source=main',
                    '--staged',
                    '--worktree',
                    '--ignore-unmerged',
                    '--overlay',
                    '--recurse-submodules',
                    '--merge',
                    '--conflict=diff3',
                    '--unified=2',
                    '--ignore-skip-worktree-bits',
                    '--pathspec-from-file=file-list.txt',
                    '--pathspec-file-nul',
                    '--',
                    'src/',
                    'docs/',
                ],
                'properties' => [
                    'gitDir' => '/repo/.git',
                    'workTree' => '/repo',
                    'cwd' => '/working/dir',
                    'source' => 'main',
                    'staged' => true,
                    'workingTree' => true,
                    'ignoreUnmerged' => true,
                    'overlay' => true,
                    'recurseSubmodules' => [true],
                    'merge' => true,
                    'conflict' => 'diff3',
                    'unified' => 2,
                    'ignoreSkipWorktreeBits' => true,
                    'pathSpecFromFile' => 'file-list.txt',
                    'pathSpecFileNul' => true,
                    'paths' => ['src/', 'docs/'],
                ],
            ],
        ];
    }

    #[Test]
    public function testSetProperties(): void
    {
        $command = $this->createCommand();
        $properties = [
            'source' => 'HEAD~1',
            'staged' => false,
            'workingTree' => true,
            'ignoreUnmerged' => false,
            'overlay' => true,
            'recurseSubmodules' => [false],
            'merge' => false,
            'conflict' => 'merge',
            'unified' => 4,
            'ignoreSkipWorktreeBits' => true,
            'pathSpecFromFile' => 'file-list.txt',
            'pathSpecFileNul' => false,
            'paths' => ['src/', 'docs/'],
        ];

        $command->setProperties($properties);

        static::assertSame('HEAD~1', $command->getSource());
        static::assertFalse($command->getStaged());
        static::assertTrue($command->getWorkingTree());
        static::assertFalse($command->getIgnoreUnmerged());
        static::assertTrue($command->getOverlay());
        static::assertSame([false], $command->getRecurseSubmodules());
        static::assertFalse($command->getMerge());
        static::assertSame('merge', $command->getConflict());
        static::assertSame(4, $command->getUnified());
        static::assertTrue($command->getIgnoreSkipWorktreeBits());
        static::assertSame('file-list.txt', $command->getPathSpecFromFile());
        static::assertFalse($command->getPathSpecFileNul());
        static::assertSame(
            ['src/' => true, 'docs/' => true],
            $command->getPaths()
        );
    }
}
