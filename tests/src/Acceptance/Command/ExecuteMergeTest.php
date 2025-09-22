<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\ExecuteMerge;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(ExecuteMerge::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-merge')]
class ExecuteMergeTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
            'type' => 'exec',
            'command' => <<<'SHELL'
                git init --initial-branch="main" {{ dirSafe }} \
                && cd {{ dirSafe }} \
                && git config user.email "test@example.com" \
                && git config user.name "Test User"
                SHELL,
        ];

        return [
            'basic-fast-forward-merge' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch feature-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout feature-branch',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/feature.txt',
                        'content' => 'Feature implementation',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add feature.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add feature"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                ],
                'properties' => [
                    'names' => ['feature-branch'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline',
                        'expectedOutput' => 'Add feature',
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/feature.txt',
                        'expected' => true,
                    ],
                ],
            ],
            'merge-with-message' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch feature-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout feature-branch',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/feature.txt',
                        'content' => 'Feature implementation',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add feature.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add feature"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/main-file.txt',
                        'content' => 'Main branch content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add main-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add main file"',
                    ],
                ],
                'properties' => [
                    'names' => ['feature-branch'],
                    'message' => 'Merge feature-branch into main',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline --grep="Merge feature-branch"',
                        'expectedOutput' => 'Merge feature-branch into main',
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/feature.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/main-file.txt',
                        'expected' => true,
                    ],
                ],
            ],
            'no-fast-forward-merge' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch feature-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout feature-branch',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/feature.txt',
                        'content' => 'Feature implementation',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add feature.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add feature"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/main-file.txt',
                        'content' => 'Main branch content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add main-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Add main file"',
                    ],
                ],
                'properties' => [
                    'names' => ['feature-branch'],
                    'fastForward' => 'no',
                    'message' => 'Merge feature branch with no-ff',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline --graph | head -5',
                        'expectedOutput' => 'Merge feature branch with no-ff',
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/feature.txt',
                        'expected' => true,
                    ],
                    [
                        'type' => 'isFile',
                        'path' => '{{ dir }}/main-file.txt',
                        'expected' => true,
                    ],
                ],
            ],
            'merge-conflict-error' => [
                'expected' => [
                    'exitCode' => 1,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/conflict-file.txt',
                        'content' => 'Original content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add conflict-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit with conflict file"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch feature-conflict',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout feature-conflict',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/conflict-file.txt',
                        'content' => 'Feature branch content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add conflict-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Modify file in feature branch"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout main',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/conflict-file.txt',
                        'content' => 'Main branch content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add conflict-file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Modify file in main branch"',
                    ],
                ],
                'properties' => [
                    'names' => ['feature-conflict'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain',
                        'expectedOutput' => 'UU conflict-file.txt',
                    ],
                ],
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

        $command = new ExecuteMerge();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame($expected['exitCode'], $result->process->getExitCode());
        static::assertSame($expected['artifacts'], $result->artifacts);

        $this->executeSteps($projectDir, $verifySteps);
    }
}
