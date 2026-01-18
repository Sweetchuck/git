<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetBranches;

#[CoversClass(GetBranches::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-branch')]
class GetBranchesTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'empty-repo' => [
                'expected' => [
                    'artifacts' => [
                        'branches' => [],
                        'currentBranch' => null,
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                ],
            ],
            'single-branch' => [
                'expected' => [
                    'artifacts' => [
                        'branches' => [
                            'refs/heads/1.x' => [
                                'isCurrentBranch' => true,
                                'push' => null,
                                'push.short' => null,
                                'refName' => 'refs/heads/1.x',
                                'refName.short' => '1.x',
                                'track' => null,
                                'track.ahead' => null,
                                'track.behind' => null,
                                'track.gone' => false,
                                'upstream' => null,
                                'upstream.short' => null,
                                'isDetached' => false,
                            ],
                        ],
                        'currentBranch' => 'refs/heads/1.x',
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
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
                'properties' => [],
            ],
            'multiple-branches' => [
                'expected' => [
                    'artifacts' => [
                        'branches' => [
                            'refs/heads/1.x' => [
                                'isCurrentBranch' => false,
                                'push' => null,
                                'push.short' => null,
                                'refName' => 'refs/heads/1.x',
                                'refName.short' => '1.x',
                                'track' => null,
                                'track.ahead' => null,
                                'track.behind' => null,
                                'track.gone' => false,
                                'upstream' => null,
                                'upstream.short' => null,
                                'isDetached' => false,
                            ],
                            'refs/heads/feature' => [
                                'isCurrentBranch' => true,
                                'push' => null,
                                'push.short' => null,
                                'refName' => 'refs/heads/feature',
                                'refName.short' => 'feature',
                                'track' => null,
                                'track.ahead' => null,
                                'track.behind' => null,
                                'track.gone' => false,
                                'upstream' => null,
                                'upstream.short' => null,
                                'isDetached' => false,
                            ],
                        ],
                        'currentBranch' => 'refs/heads/feature',
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
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
                        'command' => 'cd {{ dirSafe }} && git checkout -b feature',
                    ],
                ],
                'properties' => [],
            ],
            'with-all-option' => [
                'expected' => [
                    'artifacts' => [
                        'branches' => [
                            'refs/heads/1.x' => [
                                'isCurrentBranch' => false,
                                'push' => null,
                                'push.short' => null,
                                'refName' => 'refs/heads/1.x',
                                'refName.short' => '1.x',
                                'track' => null,
                                'track.ahead' => null,
                                'track.behind' => null,
                                'track.gone' => false,
                                'upstream' => null,
                                'upstream.short' => null,
                                'isDetached' => false,
                            ],
                            'refs/heads/feature' => [
                                'isCurrentBranch' => true,
                                'push' => null,
                                'push.short' => null,
                                'refName' => 'refs/heads/feature',
                                'refName.short' => 'feature',
                                'track' => null,
                                'track.ahead' => null,
                                'track.behind' => null,
                                'track.gone' => false,
                                'upstream' => null,
                                'upstream.short' => null,
                                'isDetached' => false,
                            ],
                            'refs/remotes/origin/1.x' => [
                                'isCurrentBranch' => false,
                                'push' => null,
                                'push.short' => null,
                                'refName' => 'refs/remotes/origin/1.x',
                                'refName.short' => 'origin/1.x',
                                'track' => null,
                                'track.ahead' => null,
                                'track.behind' => null,
                                'track.gone' => false,
                                'upstream' => null,
                                'upstream.short' => null,
                                'isDetached' => false,
                            ],
                        ],
                        'currentBranch' => 'refs/heads/feature',
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init --initial-branch="1.x" {{ dirSafe }}',
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
                        'command' => 'cd {{ dirSafe }} && git checkout -b feature',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && mkdir -p .git/refs/remotes/origin',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && cp .git/refs/heads/1.x .git/refs/remotes/origin/',
                    ],
                ],
                'properties' => [
                    'all' => true,
                ],
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

        $command = new GetBranches();
        $command->setProperties($properties);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
