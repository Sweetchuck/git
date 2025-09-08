<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\RestoreFiles;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(RestoreFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-restore')]
class RestoreFilesTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'basic-restore-modified-file' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Modified content',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'restore-staged-only' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Modified content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                    'staged' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'Modified content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'M file.txt',
                    ],
                ],
            ],
            'restore-both-staged-and-worktree' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Modified content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                    'staged' => true,
                    'workingTree' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'restore-from-specific-commit' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'First version',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "First commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Second version',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -am "Second commit"',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                    'source' => 'HEAD~1',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'First version',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain | grep "file.txt"',
                        'expectedOutput' => 'M file.txt',
                    ],
                ],
            ],
            'restore-deleted-file' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Content to restore',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && rm file.txt',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/file.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'Content to restore',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'restore-multiple-files' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'Original file 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'Original file 2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'Modified file 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'Modified file 2',
                    ],
                ],
                'properties' => [
                    'paths' => ['file1.txt', 'file2.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file1.txt',
                        'expectedOutput' => 'Original file 1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file2.txt',
                        'expectedOutput' => 'Original file 2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'restore-with-merge-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Modified content',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                    'merge' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'restore-directory' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => 'mkdir -p {{ dirSafe }}/src',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/file1.php',
                        'content' => 'Original PHP file 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/file2.php',
                        'content' => 'Original PHP file 2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/file1.php',
                        'content' => 'Modified PHP file 1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/src/file2.php',
                        'content' => 'Modified PHP file 2',
                    ],
                ],
                'properties' => [
                    'paths' => ['src/'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat src/file1.php',
                        'expectedOutput' => 'Original PHP file 1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat src/file2.php',
                        'expectedOutput' => 'Original PHP file 2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'restore-with-overlay-option' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Modified content',
                    ],
                ],
                'properties' => [
                    'paths' => ['file.txt'],
                    'overlay' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat file.txt',
                        'expectedOutput' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     * @param array<mixed> $verificationSteps
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
        array $verificationSteps = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new RestoreFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
