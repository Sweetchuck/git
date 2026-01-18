<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\FetchRefs;
use Sweetchuck\Git\FetchResult;

#[CoversClass(FetchRefs::class)]
#[Group('command-git-fetch')]
class FetchRefsTest extends CommandTestBase
{

    protected function createCommand(): FetchRefs
    {
        return new FetchRefs();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                ],
                'properties' => [],
            ],
            'basic-repo-and-refs' => [
                'expected' => [
                    'git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-true' => [
                'expected' => [
                    'git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    '--all',
                    '--append',
                    '--atomic',
                    '--dry-run',
                    '--write-fetch-head',
                    '--force',
                    '--keep',
                    '--auto-maintenance',
                    '--write-commit-graph',
                    '--prefetch',
                    '--prune',
                    '--prune-tags',
                    '--tags',
                    '--refetch',
                    '--show-forced-updates',
                    '--update-shallow',
                    '--unshallow',
                    '--set-upstream',
                    '--update-head-ok',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'all' => true,
                    'append' => true,
                    'atomic' => true,
                    'dryRun' => true,
                    'writeFetchHead' => true,
                    'force' => true,
                    'keep' => true,
                    'autoMaintenance' => true,
                    'writeCommitGraph' => true,
                    'prefetch' => true,
                    'prune' => true,
                    'pruneTags' => true,
                    'tags' => true,
                    'refetch' => true,
                    'showForcedUpdates' => true,
                    'updateShallow' => true,
                    'unshallow' => true,
                    'setUpstream' => true,
                    'updateHeadOk' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-false' => [
                'expected' => [
                    'git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    '--no-all',
                    '--no-append',
                    '--no-atomic',
                    '--no-dry-run',
                    '--no-write-fetch-head',
                    '--no-force',
                    '--no-keep',
                    '--no-auto-maintenance',
                    '--no-write-commit-graph',
                    '--no-prefetch',
                    '--no-prune',
                    '--no-prune-tags',
                    '--no-tags',
                    '--no-refetch',
                    '--no-show-forced-updates',
                    '--no-update-shallow',
                    '--no-unshallow',
                    '--no-set-upstream',
                    '--no-update-head-ok',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'all' => false,
                    'append' => false,
                    'atomic' => false,
                    'dryRun' => false,
                    'writeFetchHead' => false,
                    'force' => false,
                    'keep' => false,
                    'autoMaintenance' => false,
                    'writeCommitGraph' => false,
                    'prefetch' => false,
                    'prune' => false,
                    'pruneTags' => false,
                    'tags' => false,
                    'refetch' => false,
                    'showForcedUpdates' => false,
                    'updateShallow' => false,
                    'unshallow' => false,
                    'setUpstream' => false,
                    'updateHeadOk' => false,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-string' => [
                'expected' => [
                    'git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    '--ipv4',
                    '--depth=5',
                    '--deepen=6',
                    '--jobs=7',
                    '--shallow-since=2025',
                    '--shallow-exclude=se01',
                    '--negotiation-tip=nt01',
                    '--recurse-submodules=check',
                    '--submodule-prefix=smp01',
                    '--upload-pack=up01',
                    '--server-option=foo=bar',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'ipv' => '4',
                    'depth' => 5,
                    'deepen' => 6,
                    'jobs' => 7,
                    'shallowSince' => '2025',
                    'shallowExclude' => 'se01',
                    'negotiationTip' => 'nt01',
                    'recurseSubmodules' => ['check'],
                    'submodulePrefix' => 'smp01',
                    'uploadPack' => 'up01',
                    'serverOption' => [
                        'foo=bar',
                    ],
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'server-option-multiple' => [
                'expected' => [
                    'git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    '--server-option=key1=value1',
                    '--server-option=key2',
                    '--server-option=key3=value2',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'serverOption' => [
                        'key1=value1',
                        'key2',
                        'key3=value2',
                    ],
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'global-options-single' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/.git',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    '--prune',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'gitDir' => '/path/to/.git',
                    'prune' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'global-options-multiple' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/.git',
                    '--work-tree=/path/to/worktree',
                    '-C', '/path/to/repo',
                    'fetch',
                    '--porcelain',
                    '--verbose',
                    '--prune',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'cwd' => '/path/to/repo',
                    'gitDir' => '/path/to/.git',
                    'workTree' => '/path/to/worktree',
                    'prune' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
        ];
    }


    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'empty' => [
                'expected' => [
                    'artifacts' => [],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                        'stdError' => '',
                    ],
                ],
            ],
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'refs/heads/1.x' => [
                            'result' => FetchResult::FastForward,
                            'local' => 'sha_01',
                            'remote' => 'sha_02',
                            'ref' => 'refs/heads/1.x',
                        ],
                        'refs/heads/2.x' => [
                            'result' => FetchResult::UpToDate,
                            'local' => 'sha_03',
                            'remote' => 'sha_03',
                            'ref' => 'refs/heads/2.x',
                        ],
                        'refs/remotes/upstream/3.x' => [
                            'result' => FetchResult::UpToDate,
                            'local' => 'sha_04',
                            'remote' => 'sha_04',
                            'ref' => 'refs/remotes/upstream/3.x',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode(
                            "\n",
                            [
                                '  sha_01 sha_02 refs/heads/1.x',
                                '= sha_03 sha_03 refs/heads/2.x',
                                '= sha_04 sha_04 refs/remotes/upstream/3.x',
                            ],
                        ),
                        'stdError' => '',
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<array<string, int|string>> $processOutcomes
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(array $expected, array $properties, array $processOutcomes = []): void
    {
        if (!array_key_exists('processFactory', $properties)) {
            $properties['processFactory'] = $this->createProcessFactory($processOutcomes);
        }

        $command = $this->createCommand();
        $command->setProperties($properties);
        $result = $command->execute();

        if (isset($expected['exitCode'])) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (isset($expected['stdOutput'])) {
            static::assertSame($expected['stdOutput'], $result->process->getOutput());
        }

        if (isset($expected['stdError'])) {
            static::assertSame($expected['stdError'], $result->process->getErrorOutput());
        }

        if (isset($expected['artifacts'])) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
