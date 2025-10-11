<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\MoveBranch;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(MoveBranch::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-branch')]
class MoveBranchTest extends CommandTestBase
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
            'rename-current-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
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
                    'oldName' => 'main',
                    'newName' => 'master',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list master',
                        'expectedOutput' => '* master',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list main',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'rename-non-current-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
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
                        'command' => 'cd {{ dirSafe }} && git branch feature-branch',
                    ],
                ],
                'properties' => [
                    'oldName' => 'feature-branch',
                    'newName' => 'feature-renamed',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list feature-renamed',
                        'expectedOutput' => 'feature-renamed',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list feature-branch',
                        'expectedOutput' => '',
                    ],
                ],
            ],
            'rename-branch-with-commits' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
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
                        'command' => 'cd {{ dirSafe }} && git branch develop',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout develop',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/feature.txt',
                        'content' => 'Feature content',
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
                    'oldName' => 'develop',
                    'newName' => 'development',
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list development',
                        'expectedOutput' => 'development',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout development && ls feature.txt 2>&1',
                        'expectedOutput' => 'feature.txt',
                    ],
                ],
            ],
            'error-branch-not-found' => [
                'expected' => [
                    'exitCode' => 128,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
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
                    'oldName' => 'non-existent-branch',
                    'newName' => 'renamed-branch',
                ],
                'verifySteps' => [],
            ],
            'error-target-branch-exists' => [
                'expected' => [
                    'exitCode' => 128,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
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
                        'command' => 'cd {{ dirSafe }} && git branch feature',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch existing-branch',
                    ],
                ],
                'properties' => [
                    'oldName' => 'feature',
                    'newName' => 'existing-branch',
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

        $command = new MoveBranch();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame($expected['exitCode'], $result->process->getExitCode());
        static::assertSame($expected['artifacts'], $result->artifacts);

        $this->executeSteps($projectDir, $verifySteps);
    }
}
