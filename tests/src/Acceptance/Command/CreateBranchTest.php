<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CreateBranch;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(CreateBranch::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-branch')]
class CreateBranchTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic-branch-creation' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="main" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
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
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'name' => 'feature-branch',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list feature-branch',
                        'expectedOutput' => 'feature-branch',
                    ],
                ],
            ],
            'branch-with-start-point' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="main" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
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
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/file1.txt',
                        'content' => 'First file',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add file1"',
                    ],
                ],
                'properties' => [
                    'name' => 'feature-from-head-1',
                    'startPoint' => 'HEAD~1',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list feature-from-head-1',
                        'expectedOutput' => 'feature-from-head-1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => implode(
                            ' && ',
                            [
                                'cd {{ dirSafe }}',
                                'git checkout feature-from-head-1',
                                'test ! -f file1.txt',
                                'echo "File does not exist"',
                            ],
                        ),
                        'expectedOutput' => 'File does not exist',
                    ],
                ],
            ],
            'branch-with-force' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="main" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
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
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch existing-branch',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/new-file.txt',
                        'content' => 'New content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add new-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add new file"',
                    ],
                ],
                'properties' => [
                    'name' => 'existing-branch',
                    'force' => true,
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout existing-branch && ls new-file.txt 2>&1',
                        'expectedOutput' => 'new-file.txt',
                    ],
                ],
            ],
            'branch-already-exists-error' => [
                'expected' => [
                    'exitCode' => 128,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="main" {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
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
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch duplicate-branch',
                    ],
                ],
                'properties' => [
                    'name' => 'duplicate-branch',
                ],
                'verifySteps' => [],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     * @param array<mixed> $verifySteps
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
        array $verifySteps = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new CreateBranch();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame($expected['exitCode'], $result->process->getExitCode());
        static::assertSame($expected['artifacts'], $result->artifacts);

        $this->executeSteps($projectDir, $verifySteps);
    }
}
