<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\MoveFiles;
use Sweetchuck\Git\CommandFactory;
use Sweetchuck\Git\Repository;

#[CoversClass(MoveFiles::class)]
#[Group('command-git-mv')]
class MoveFilesTest extends CommandTestBase
{
    #[Test]
    public function testMoveFile(): void
    {
        $dir = $this->createTempDirectory();

        $this->executeSteps($dir, [
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git init',
            ],
            [
                'type' => 'createFile',
                'path' => '{{ dir }}/original.txt',
                'content' => 'Test content',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git add original.txt',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
            ],
        ]);

        $repo = new Repository($dir, new CommandFactory());

        $repo->moveFiles([
            'paths' => ['original.txt', 'renamed.txt'],
        ]);

        static::assertFileExists("$dir/renamed.txt");
        static::assertFileDoesNotExist("$dir/original.txt");
    }

    #[Test]
    public function testMoveFileWithForce(): void
    {
        $dir = $this->createTempDirectory();

        $this->executeSteps($dir, [
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git init',
            ],
            [
                'type' => 'createFile',
                'path' => '{{ dir }}/source.txt',
                'content' => 'Source content',
            ],
            [
                'type' => 'createFile',
                'path' => '{{ dir }}/target.txt',
                'content' => 'Target content',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git add .',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
            ],
        ]);

        $repo = new Repository($dir, new CommandFactory());

        $repo->moveFiles([
            'force' => true,
            'paths' => ['source.txt', 'target.txt'],
        ]);

        $this->executeSteps($dir, [
            [
                'type' => 'isFile',
                'path' => '{{ dir }}/target.txt',
                'expected' => true,
            ],
            [
                'type' => 'isFile',
                'path' => '{{ dir }}/source.txt',
                'expected' => false,
            ],
        ]);
    }

    #[Test]
    public function testMoveMultipleFilesToDirectory(): void
    {
        $dir = $this->createTempDirectory();

        $this->executeSteps($dir, [
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git init',
            ],
            [
                'type' => 'createFile',
                'path' => '{{ dir }}/file1.txt',
                'content' => 'Content 1',
            ],
            [
                'type' => 'createFile',
                'path' => '{{ dir }}/file2.txt',
                'content' => 'Content 2',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && mkdir subdir',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git add .',
            ],
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
            ],
        ]);

        $repo = new Repository($dir, new CommandFactory());

        $repo->moveFiles([
            'paths' => ['file1.txt', 'file2.txt', 'subdir/'],
        ]);

        $this->executeSteps($dir, [
            [
                'type' => 'isFile',
                'path' => '{{ dir }}/subdir/file1.txt',
                'expected' => true,
            ],
            [
                'type' => 'isFile',
                'path' => '{{ dir }}/subdir/file2.txt',
                'expected' => true,
            ],
            [
                'type' => 'isFile',
                'path' => '{{ dir }}/file1.txt',
                'expected' => false,
            ],
            [
                'type' => 'isFile',
                'path' => '{{ dir }}/file2.txt',
                'expected' => false,
            ],
        ]);
    }
}
