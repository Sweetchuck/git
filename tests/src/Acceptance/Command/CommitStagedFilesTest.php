<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\CommitStagedFiles;

#[CoversClass(CommitStagedFiles::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-commit')]
class CommitStagedFilesTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic-commit-with-message' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                ],
                'properties' => [
                    'message' => 'Initial commit',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline',
                        'expectedOutput' => 'Initial commit',
                    ],
                ],
            ],
            'commit-with-author' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Test content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                ],
                'properties' => [
                    'message' => 'Commit with custom author',
                    'author' => 'Custom Author <custom@example.com>',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --format="%an <%ae>" -1',
                        'expectedOutput' => 'Custom Author <custom@example.com>',
                    ],
                ],
            ],
            'commit-with-signoff' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/signoff.txt',
                        'content' => 'Signoff test',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add signoff.txt',
                    ],
                ],
                'properties' => [
                    'message' => 'Commit with signoff',
                    'signoff' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --format="%B" -1',
                        'expectedOutput' => 'Signed-off-by: Test User <test@example.com>',
                    ],
                ],
            ],
            'allow-empty-commit' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                ],
                'properties' => [
                    'message' => 'Empty commit',
                    'allowEmpty' => true,
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline',
                        'expectedOutput' => 'Empty commit',
                    ],
                ],
            ],
            'commit-specific-paths' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.email "test@example.com"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git config user.name "Test User"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'File 1 content',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file2.txt',
                        'content' => 'File 2 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add .',
                    ],
                ],
                'properties' => [
                    'message' => 'Commit specific file',
                    'paths' => ['file1.txt'],
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'A  file2.txt',
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

        $command = new CommitStagedFiles();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
