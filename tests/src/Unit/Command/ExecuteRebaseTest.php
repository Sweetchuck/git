<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\ExecuteRebase;

#[CoversClass(ExecuteRebase::class)]
#[Group('command-git-rebase')]
class ExecuteRebaseTest extends CommandTestBase
{

    protected function createCommand(): ExecuteRebase
    {
        return new ExecuteRebase();
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
                    'rebase',
                ],
                'properties' => [],
            ],
            'basic-branch' => [
                'expected' => [
                    'git',
                    'rebase',
                    'main',
                ],
                'properties' => [
                    'branch' => 'main',
                ],
            ],
            'repository-and-branch' => [
                'expected' => [
                    'git',
                    'rebase',
                    'origin',
                    'feature-42',
                ],
                'properties' => [
                    'repository' => 'origin',
                    'branch' => 'feature-42',
                ],
            ],
            'bool-options-true' => [
                'expected' => [
                    'git',
                    'rebase',
                    '--keep-base',
                    '--apply',
                    '--empty=drop',
                    '--keep-empty',
                    '--reapply-cherry-picks',
                    '--allow-empty-message',
                    '--merge',
                    '--rerere-autoupdate',
                    '--verify',
                    '--no-ff',
                    '--fork-point',
                    '--ignore-whitespace',
                    '--committer-date-is-author-date',
                    '--reset-author-date',
                    '--signoff',
                    '--root',
                    '--autosquash',
                    '--autostash',
                    '--update-refs',
                    'main',
                ],
                'properties' => [
                    'keepBase' => true,
                    'apply' => true,
                    'empty' => 'drop',
                    'keepEmpty' => true,
                    'reapplyCherryPicks' => true,
                    'allowEmptyMessage' => true,
                    'merge' => true,
                    'rerereAutoupdate' => true,
                    'verify' => true,
                    'noFastForward' => true,
                    'forkPoint' => true,
                    'ignoreWhitespace' => true,
                    'committerDateIsAuthorDate' => true,
                    'resetAuthorDate' => true,
                    'signoff' => true,
                    'root' => true,
                    'autoSquash' => true,
                    'autoStash' => true,
                    'updateRefs' => true,
                    'branch' => 'main',
                ],
            ],
            'bool-options-false' => [
                'expected' => [
                    'git',
                    'rebase',
                    '--no-keep-base',
                    '--no-apply',
                    '--empty=keep',
                    '--no-keep-empty',
                    '--no-reapply-cherry-picks',
                    '--no-allow-empty-message',
                    '--no-merge',
                    '--no-rerere-autoupdate',
                    '--no-verify',
                    '--no-fork-point',
                    '--no-ignore-whitespace',
                    '--no-committer-date-is-author-date',
                    '--no-reset-author-date',
                    '--no-signoff',
                    '--no-root',
                    '--no-autosquash',
                    '--no-autostash',
                    '--no-update-refs',
                    'main',
                ],
                'properties' => [
                    'keepBase' => false,
                    'apply' => false,
                    'empty' => 'keep',
                    'keepEmpty' => false,
                    'reapplyCherryPicks' => false,
                    'allowEmptyMessage' => false,
                    'merge' => false,
                    'rerereAutoupdate' => false,
                    'verify' => false,
                    'forkPoint' => false,
                    'ignoreWhitespace' => false,
                    'committerDateIsAuthorDate' => false,
                    'resetAuthorDate' => false,
                    'signoff' => false,
                    'root' => false,
                    'autoSquash' => false,
                    'autoStash' => false,
                    'updateRefs' => false,
                    'branch' => 'main',
                ],
            ],
            'string-options' => [
                'expected' => [
                    'git',
                    'rebase',
                    '--rebase-merges=rebase-cousins',
                    '--onto=abc1234',
                    '-C', '5',
                    '--whitespace=fix',
                    '--strategy=recursive',
                    '--strategy-option=foo=bar',
                    '--gpg-sign=key123',
                    '--exec=command1',
                    '--exec=command2',
                    'main',
                ],
                'properties' => [
                    'onTo' => 'abc1234',
                    'whitespace' => 'fix',
                    'gpgSign' => 'key123',
                    'exec' => ['command1', 'command2'],
                    'strategies' => [
                        'recursive' => [
                            'options' => [
                                'foo=bar' => true,
                            ],
                        ],
                    ],
                    'rebaseMerges' => 'rebase-cousins',
                    'context' => 5,
                    'branch' => 'main',
                ],
            ],
            'global-options-single' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/.git',
                    'rebase',
                    '--autostash',
                    'main',
                ],
                'properties' => [
                    'gitDir' => '/path/to/.git',
                    'autoStash' => true,
                    'branch' => 'main',
                ],
            ],
            'global-options-multiple' => [
                'expected' => [
                    'git',
                    '--git-dir=/path/to/.git',
                    '--work-tree=/path/to/worktree',
                    '-C', '/path/to/repo',
                    'rebase',
                    '--autostash',
                    'origin',
                ],
                'properties' => [
                    'cwd' => '/path/to/repo',
                    'gitDir' => '/path/to/.git',
                    'workTree' => '/path/to/worktree',
                    'autoStash' => true,
                    'repository' => 'origin',
                ],
            ],
        ];
    }
}
