<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\FetchRefs;

#[CoversClass(FetchRefs::class)]
#[Group('command-git-push')]
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
                ],
                'properties' => [],
            ],
            'basic-repo-and-refs' => [
                'expected' => [
                    'git',
                    'fetch',
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
}
