<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\DeleteBranch;

#[CoversClass(DeleteBranch::class)]
#[Group('command-git-branch')]
class DeleteBranchTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'single-branch' => [
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
                        'command' => 'cd {{ dirSafe }} && git checkout -b test-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                ],
                'properties' => [
                    'names' => ['test-branch'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list test-branch',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'multiple-branches' => [
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
                        'command' => 'cd {{ dirSafe }} && git checkout -b branch-1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout -b branch-2',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout -b branch-3',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                ],
                'properties' => [
                    'names' => ['branch-1', 'branch-3'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list',
                        'expectedOutput' => 'branch-2',
                    ],
                ],
            ],
            'with-force' => [
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
                        'command' => 'cd {{ dirSafe }} && git checkout -b unmerged-branch',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/test-file.txt',
                        'content' => 'test content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add test-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Test commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                ],
                'properties' => [
                    'names' => ['unmerged-branch'],
                    'force' => true,
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list unmerged-branch',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'non-existent-branch' => [
                'expected' => [
                    'exitCode' => 1,
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
                    'names' => ['non-existent-branch'],
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

        $command = new DeleteBranch();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame($expected['exitCode'], $result->process->getExitCode());
        static::assertSame($expected['artifacts'], $result->artifacts);

        if (!empty($verifySteps)) {
            $this->executeSteps($projectDir, $verifySteps);
        }
    }
}
