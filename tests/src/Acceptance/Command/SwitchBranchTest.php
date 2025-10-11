<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\SwitchBranch;

#[CoversClass(SwitchBranch::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-switch')]
class SwitchBranchTest extends CommandTestBase
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
            'basic-switch-to-existing-branch' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
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
                ],
                'properties' => [
                    'name' => 'feature-branch',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'feature-branch',
                    ],
                ],
            ],
            'create-new-branch-normal' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'createMethod' => 'normal',
                    'name' => 'new-feature',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'new-feature',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --list new-feature',
                        'expectedOutput' => '* new-feature',
                    ],
                ],
            ],
            'create-new-branch-with-start-point' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
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
                    'createMethod' => 'normal',
                    'name' => 'feature-from-head-1',
                    'startPoint' => 'HEAD~1',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'feature-from-head-1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && test ! -f file1.txt && echo "File does not exist"',
                        'expectedOutput' => 'File does not exist',
                    ],
                ],
            ],
            'force-create-existing-branch' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
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
                    'createMethod' => 'force',
                    'name' => 'existing-branch',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'existing-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && test -f new-file.txt && echo "File exists"',
                        'expectedOutput' => 'File exists',
                    ],
                ],
            ],
            'detach-mode' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'detach' => true,
                    'name' => 'HEAD~0',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => '',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git status --porcelain -b | head -1',
                        'expectedOutput' => '## HEAD (no branch)',
                    ],
                ],
            ],
            'switch-with-discard-changes' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
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
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Modified Project',
                    ],
                ],
                'properties' => [
                    'discardChanges' => true,
                    'name' => 'feature-branch',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'feature-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cat README.md',
                        'expectedOutput' => '# Test Project',
                    ],
                ],
            ],
            'orphan-branch' => [
                'expected' => [
                    'exitCode' => 0,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'orphan' => true,
                    'name' => 'orphan-branch',
                ],
                'verificationSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git branch --show-current',
                        'expectedOutput' => 'orphan-branch',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git log --oneline 2>&1 || echo "No commits"',
                        'expectedOutput' => 'No commits',
                    ],
                ],
            ],
            'non-existent-branch-error' => [
                'expected' => [
                    'exitCode' => 128,
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/README.md',
                        'content' => '# Test Project',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Initial commit"',
                    ],
                ],
                'properties' => [
                    'name' => 'non-existent-branch',
                ],
                'verificationSteps' => [],
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

        $command = new SwitchBranch();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('exitCode', $expected)) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        $this->executeSteps($projectDir, $verificationSteps);
    }
}
