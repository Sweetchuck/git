<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\FetchRefs;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\FetchResult;

#[CoversClass(FetchRefs::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-fetch')]
class FetchRefsTest extends CommandTestBase
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
            'fetch-single-branch' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/heads/feature-1' => [
                            'result' => FetchResult::FastForward,
                            'ref' => 'refs/heads/feature-1',
                        ],
                        'refs/remotes/upstream/feature-1' => [
                            'result' => FetchResult::UpToDate,
                            'ref' => 'refs/remotes/upstream/feature-1',
                        ]
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
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git add README.md \
                            && git commit -m "Initial commit"
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git switch --create='feature-1' \
                            && echo '<?php echo "Hello World!";' > index.php \
                            && git add index.php \
                            && git commit --message="Add feature-1" \
                            && git push upstream feature-1 --set-upstream \
                            && git reset 'HEAD^' --hard
                            SHELL,
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git --no-pager log -1 --format='%s'
                            SHELL,
                        'expectedExitCode' => 0,
                        'expectedOutput' => 'Initial commit',
                    ],
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git switch main
                            SHELL,
                        'expectedExitCode' => 0,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['feature-1:feature-1'],
                ],
                'verifySteps' => [
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && git --no-pager log -1 --format='%s' feature-1
                            SHELL,
                        'expectedExitCode' => 0,
                        'expectedOutput' => 'Add feature-1',
                    ],
                ],
            ],
            'forced-update' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/heads/main' => [
                            'result' => FetchResult::ForcedUpdate,
                            'ref' => 'refs/heads/main',
                        ],
                        'refs/remotes/upstream/main' => [
                            'result' => FetchResult::UpToDate,
                            'ref' => 'refs/remotes/upstream/main',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && touch a && git add a && git commit -m "Init" \
                            && git push upstream main \
                            && echo "v2" > a && git add a && git commit --amend -m "Init amended" \
                            && git push upstream main --force \
                            && git reset --hard HEAD@{1} \
                            && git checkout --detach
                            SHELL,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['main:main'],
                    'force' => true,
                ],
            ],
            'new-ref' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/heads/feature-2' => [
                            'result' => FetchResult::NewRef,
                            'ref' => 'refs/heads/feature-2',
                        ],
                        'refs/remotes/upstream/feature-2' => [
                            'result' => FetchResult::UpToDate,
                            'ref' => 'refs/remotes/upstream/feature-2',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && touch a && git add a && git commit -m "Init" \
                            && git push upstream main \
                            && git checkout -b feature-2 \
                            && touch b && git add b && git commit -m "Feature 2" \
                            && git push upstream feature-2 \
                            && git checkout main \
                            && git branch -D feature-2
                            SHELL,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['feature-2:feature-2'],
                ],
            ],
            'prune' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/remotes/upstream/main' => [
                            'result' => FetchResult::UpToDate,
                            'ref' => 'refs/remotes/upstream/main',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && touch a && git add a && git commit -m "Init" \
                            && git push upstream main \
                            && git checkout -b feature-1 \
                            && git push upstream feature-1 \
                            && git checkout main \
                            && git fetch upstream \
                            && git push upstream :feature-1
                            SHELL,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'prune' => true,
                ],
            ],
            'updated-tag' => [
                'expected' => [
                    'exitCode' => 0,
                    'artifacts' => [
                        'refs/heads/v1' => [
                            'result' => FetchResult::NewRef,
                            'ref' => 'refs/heads/v1',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && touch a && git add a && git commit -m "Init" \
                            && git tag v1 \
                            && git push upstream v1 \
                            && touch b && git add b && git commit -m "Update" \
                            && git tag -f v1 \
                            && git push upstream v1 --force \
                            && git tag -f v1 HEAD~1
                            SHELL,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['v1:v1'],
                    'force' => true,
                ],
            ],
            'rejected' => [
                'expected' => [
                    'exitCode' => 1,
                    'artifacts' => [
                        'refs/heads/main' => [
                            'result' => FetchResult::Rejected,
                            'ref' => 'refs/heads/main',
                        ],
                        'refs/remotes/upstream/main' => [
                            'result' => FetchResult::UpToDate,
                            'ref' => 'refs/remotes/upstream/main',
                        ],
                    ],
                ],
                'initSteps' => [
                    $initStepGitInitCommon,
                    [
                        'type' => 'exec',
                        'command' => <<<'SHELL'
                            cd {{ dirSafe }}/workspace \
                            && touch a && git add a && git commit -m "Init" \
                            && git push upstream main \
                            && echo "upstream" > a && git add a && git commit -m "Upstream change" \
                            && git push upstream main \
                            && git reset --hard HEAD~1 \
                            && echo "local" > a && git add a && git commit -m "Local change" \
                            && git checkout --detach
                            SHELL,
                    ],
                ],
                'properties' => [
                    'repository' => 'upstream',
                    'refs' => ['main:main'],
                    'force' => false,
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

        $command = new FetchRefs();
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

        if (array_key_exists('artifacts', $expected)) {
            if ($expected['artifacts'] === null) {
                static::assertNull($result->artifacts);
            } else {
                static::assertSame(
                    array_keys($expected['artifacts']),
                    array_keys($result->artifacts),
                    'artifacts keys match',
                );

                foreach ($expected['artifacts'] as $refName => $item) {
                    static::assertSame(
                        $item['result'],
                        $result->artifacts[$refName]['result'],
                        "artifacts.$refName.result match",
                    );
                    // Keys "local" and "remote" are random SHA.
                    static::assertSame(
                        $item['ref'],
                        $result->artifacts[$refName]['ref'],
                        "artifacts.$refName.ref match",
                    );
                }
            }
        }

        $this->executeSteps($projectDir, $verifySteps);
    }
}
