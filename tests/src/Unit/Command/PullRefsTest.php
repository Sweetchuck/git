<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\PullRefs;

#[CoversClass(PullRefs::class)]
#[Group('command-git-pull')]
class PullRefsTest extends CommandTestBase
{

    protected function createCommand(): PullRefs
    {
        return new PullRefs();
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
                    'pull',
                ],
                'properties' => [],
            ],
            'basic-repo-and-refs' => [
                'expected' => [
                    'git',
                    'pull',
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
                    'pull',
                    '--all',
                    '--append',
                    '--atomic',
                    '--squash',
                    '--verify',
                    '--verify-signatures',
                    '--autostash',
                    '--allow-unrelated-histories',
                    '--rebase',
                    '--unshallow',
                    '--update-shallow',
                    '--dry-run',
                    '--force',
                    '--keep',
                    '--prefetch',
                    '--prune',
                    '--tags',
                    '--set-upstream',
                    '--show-forced-updates',
                    '--gpg-sign',
                    '--log',
                    '--ff',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'all' => true,
                    'append' => true,
                    'atomic' => true,
                    'commit' => true,
                    'squash' => true,
                    'verify' => true,
                    'verifySignatures' => true,
                    'autoStash' => true,
                    'allowUnrelatedHistories' => true,
                    'rebase' => true,
                    'unshallow' => true,
                    'updateShallow' => true,
                    'dryRun' => true,
                    'force' => true,
                    'keep' => true,
                    'prefetch' => true,
                    'prune' => true,
                    'tags' => true,
                    'setUpstream' => true,
                    'showForcedUpdates' => true,
                    'gpgSign' => true,
                    'log' => true,
                    'fastForward' => true,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-false' => [
                'expected' => [
                    'git',
                    'pull',
                    '--no-all',
                    '--no-append',
                    '--no-atomic',
                    '--no-squash',
                    '--no-verify',
                    '--no-verify-signatures',
                    '--no-autostash',
                    '--no-allow-unrelated-histories',
                    '--no-rebase',
                    '--no-unshallow',
                    '--no-update-shallow',
                    '--no-dry-run',
                    '--no-force',
                    '--no-keep',
                    '--no-prefetch',
                    '--no-prune',
                    '--no-tags',
                    '--no-set-upstream',
                    '--no-show-forced-updates',
                    '--no-gpg-sign',
                    '--no-log',
                    '--no-ff',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'all' => false,
                    'append' => false,
                    'atomic' => false,
                    'commit' => false,
                    'squash' => false,
                    'verify' => false,
                    'verifySignatures' => false,
                    'autoStash' => false,
                    'allowUnrelatedHistories' => false,
                    'rebase' => false,
                    'unshallow' => false,
                    'updateShallow' => false,
                    'dryRun' => false,
                    'force' => false,
                    'keep' => false,
                    'prefetch' => false,
                    'prune' => false,
                    'tags' => false,
                    'setUpstream' => false,
                    'showForcedUpdates' => false,
                    'gpgSign' => false,
                    'log' => false,
                    'fastForward' => false,
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'all-string' => [
                'expected' => [
                    'git',
                    'pull',
                    '--gpg-sign=gpg01',
                    '--log=3',
                    '--jobs=7',
                    '--depth=5',
                    '--deepen=6',
                    '--cleanup=prune',
                    '--shallow-since=2025',
                    '--shallow-exclude=se01',
                    '--negotiation-tip=nt01',
                    '--upload-pack=up01',
                    '--server-option=foo=bar',
                    '--ipv4',
                    '--ff-only',
                    '--strategy=recursive',
                    '--strategy-option=foo=bar',
                    '--recurse-submodules=check',
                    'origin',
                    'main',
                ],
                'properties' => [
                    'gpgSign' => 'gpg01',
                    'log' => 3,
                    'jobs' => 7,
                    'depth' => 5,
                    'deepen' => 6,
                    'cleanup' => 'prune',
                    'shallowSince' => '2025',
                    'shallowExclude' => 'se01',
                    'negotiationTip' => 'nt01',
                    'uploadPack' => 'up01',
                    'serverOption' => [
                        'foo=bar',
                    ],
                    'ipv' => '4',
                    'fastForward' => 'only',
                    'strategies' => [
                        'recursive' => [
                            'options' => [
                                'foo=bar' => true,
                            ],
                        ],
                    ],
                    'recurseSubmodules' => ['check'],
                    'repository' => 'origin',
                    'refs' => ['main'],
                ],
            ],
            'server-option-multiple' => [
                'expected' => [
                    'git',
                    'pull',
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
                    'pull',
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
                    'pull',
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
