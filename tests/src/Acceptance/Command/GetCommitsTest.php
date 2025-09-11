<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetCommits;

#[CoversClass(GetCommits::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-log')]
class GetCommitsTest extends CommandTestBase
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
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [
                        'commits' => [],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                ],
            ],
            'single-commit' => [
                'expected' => [
                    'artifacts' => [
                        'commits' => [
                            [
                                'authorEmail' => 'test@example.com',
                                'authorName' => 'Test User',
                                'commitMessage.subject' => 'Initial commit',
                            ],
                        ],
                    ],
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
                        'command' => 'cd {{ dirSafe }} && git commit --message="Initial commit"',
                    ],
                ],
                'properties' => [],
            ],
            'multiple-commits' => [
                'expected' => [
                    'artifacts' => [
                        'commits' => [
                            [
                                'authorEmail' => 'test@example.com',
                                'authorName' => 'Test User',
                                'commitMessage.subject' => 'Second commit',
                            ],
                            [
                                'authorEmail' => 'test@example.com',
                                'authorName' => 'Test User',
                                'commitMessage.subject' => 'Initial commit',
                            ],
                        ],
                    ],
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
                        'path' => '{{ dir }}/file.txt',
                        'content' => 'Test content',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git add file.txt',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && git commit -m "Second commit"',
                    ],
                ],
                'properties' => [],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        $command = new GetCommits();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)) {
            if (array_key_exists('commits', $expected['artifacts'])) {
                static::assertIsArray($result->artifacts['commits']);
                static::assertCount(
                    count($expected['artifacts']['commits']),
                    $result->artifacts['commits'],
                );

                $actualCommits = array_values($result->artifacts['commits']);
                foreach ($expected['artifacts']['commits'] as $index => $expectedCommit) {
                    static::assertArrayHasKey($index, $actualCommits);
                    foreach ($expectedCommit as $key => $expectedValue) {
                        static::assertArrayHasKey($key, $actualCommits[$index]);
                        static::assertSame($expectedValue, $actualCommits[$index][$key]);
                    }
                }
            } else {
                static::assertSame($expected['artifacts'], $result->artifacts);
            }
        }
    }
}
