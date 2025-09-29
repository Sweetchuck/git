<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\ExecuteRebase;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;

#[CoversClass(ExecuteRebase::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-rebase')]
class ExecuteRebaseTest extends CommandTestBase
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
            'basic-rebase-simple' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => null,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    // Create initial commit on main
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
                    // Create and switch to feature branch
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch feature-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout feature-branch',
                    ],
                    // Add commit to feature branch
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
                    // Add another commit to main (create a divergent history)
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
                    // Switch back to feature branch for rebase
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git checkout feature-branch',
                    ],
                ],
                'properties' => [
                    'branch' => 'main',
                ],
                'verifySteps' => [
                    // Verify rebase was successful - feature branch should have main's changes
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline --graph',
                        'expectedOutput' => 'Add feature',
                    ],
                    // Verify both files exist (feature.txt from feature branch, main-file.txt from main branch)
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
                    // Verify we're still on feature-branch
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'feature-branch',
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

        $command = new ExecuteRebase();
        $command->setProperties($properties);
        $result = $command->execute();

        static::assertSame($expected['exitCode'], $result->process->getExitCode());
        static::assertSame($expected['artifacts'], $result->artifacts);

        $this->executeSteps($projectDir, $verifySteps);
    }
}
