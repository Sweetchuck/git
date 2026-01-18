<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\PushRefs;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\PushResult;

#[CoversClass(PushRefs::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-push')]
class PushRefsTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initStepGitInitCommon = [
        'type' => 'exec',
        'command' => <<<'SHELL'
            git init --bare {{ dirSafe }}/upstream.git \
            && git init --initial-branch="main" {{ dirSafe }}/workspace \
            && cd {{ dirSafe }}/workspace \
            && git config user.email "test@example.com" \
            && git config user.name "Test User" \
            && git remote add upstream ../upstream.git
            SHELL,
        ];

        return [
            'push-single-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/heads/feature-1:refs/heads/feature-1' => [
                            'result' => PushResult::NewRef,
                            'refNameLocal' => 'refs/heads/feature-1',
                            'refNameRemote' => 'refs/heads/feature-1',
                            'message' => 'new branch',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git checkout -b feature-1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/feature1.txt',
                        'content' => 'Feature 1 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add feature1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Add feature 1"',
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['feature-1'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/upstream.git && git branch --list',
                        'expectedOutput' => 'feature-1',
                    ],
                ],
            ],
            'push-multiple-branches' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/heads/feature-1:refs/heads/feature-1' => [
                            'result' => PushResult::NewRef,
                            'refNameLocal' => 'refs/heads/feature-1',
                            'refNameRemote' => 'refs/heads/feature-1',
                            'message' => 'new branch',
                        ],
                        'refs/heads/feature-2:refs/heads/feature-2' => [
                            'result' => PushResult::NewRef,
                            'refNameLocal' => 'refs/heads/feature-2',
                            'refNameRemote' => 'refs/heads/feature-2',
                            'message' => 'new branch',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git checkout -b feature-1',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/feature1.txt',
                        'content' => 'Feature 1 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add feature1.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Add feature 1"',
                    ],
                    // Create feature branch 2
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git checkout main',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git checkout -b feature-2',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/feature2.txt',
                        'content' => 'Feature 2 content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add feature2.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Add feature 2"',
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['feature-1', 'feature-2'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/upstream.git && git branch --list',
                        'expectedOutput' => 'feature-1',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/upstream.git && git branch --list',
                        'expectedOutput' => 'feature-2',
                    ],
                ],
            ],
            'delete-remote-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/README.md',
                        'content' => '# Test Repository',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add README.md',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Initial commit"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git checkout -b obsolete-feature',
                    ],
                    [
                        'type' => 'createFile',
                        'path' => '{{ dir }}/workspace/obsolete.txt',
                        'content' => 'Obsolete feature content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git add obsolete.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git commit -m "Add obsolete feature"',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/workspace && git push upstream obsolete-feature',
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['obsolete-feature'],
                    'delete' => true,
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }}/upstream.git && git branch --list',
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
        $properties['workingDirectory'] = "$projectDir/workspace";
        $this->executeSteps($projectDir, $initSteps);

        $command = new PushRefs();
        $command->setProperties($properties);
        $result = $command->execute();

        $stdOutput = $result->process->getOutput();
        $stdError = $result->process->getErrorOutput();
        static::assertSame(
            $expected['exitCode'],
            $result->process->getExitCode(),
            <<< TEXT
                --== stdOutout ==--
                {$stdOutput}

                --== stdError ==--
                {$stdError}
                TEXT,
        );
        static::assertSame($expected['artifacts'], $result->artifacts);

        $this->executeSteps($projectDir, $verifySteps);
    }
}
