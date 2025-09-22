<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\ExecuteMerge;

#[CoversClass(ExecuteMerge::class)]
#[Group('command-git-merge')]
class ExecuteMergeTest extends CommandTestBase
{
    protected function createCommand(): ExecuteMerge
    {
        return new ExecuteMerge();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => ['git', 'merge', 'issue-42'],
                'properties' => [
                    'names' => ['issue-42'],
                ],
            ],
            'all-in-one-true' => [
                'expected' => [
                    'git',
                    'merge',
                    '--signoff',
                    '--squash',
                    '--verify',
                    '--verify-signatures',
                    '--rerere-autoupdate',
                    '--overwrite-ignore',
                    '--commit',
                    '--autostash',
                    '--edit',
                    '--file=my-file-01',
                    '-m', 'my-message-01',
                    '--into-name=my-into-name-01',
                    '--cleanup=strip',
                    '--gpg-sign',
                    '--log',
                    '--ff',
                    'issue-42',
                    'issue-43',
                ],
                'properties' => [
                    'signoff' => true,
                    'squash' => true,
                    'verify' => true,
                    'verifySignatures' => true,
                    'rerereAutoupdate' => true,
                    'overwriteIgnore' => true,
                    'commit' => true,
                    'autoStash' => true,
                    'edit' => true,
                    'file' => 'my-file-01',
                    'message' => 'my-message-01',
                    'intoName' => 'my-into-name-01',
                    'cleanup' => 'strip',
                    'gpgSign' => true,
                    'log' => true,
                    'fastForward' => 'yes',
                    'names' => ['issue-42', 'issue-43'],
                ],
            ],
            'all-in-one-false' => [
                'expected' => [
                    'git',
                    'merge',
                    '--no-signoff',
                    '--no-squash',
                    '--no-verify',
                    '--no-verify-signatures',
                    '--no-rerere-autoupdate',
                    '--no-overwrite-ignore',
                    '--no-commit',
                    '--no-autostash',
                    '--no-edit',
                    '--no-gpg-sign',
                    '--no-log',
                    '--no-ff',
                    'issue-42',
                    'issue-43',
                ],
                'properties' => [
                    'signoff' => false,
                    'squash' => false,
                    'verify' => false,
                    'verifySignatures' => false,
                    'rerereAutoupdate' => false,
                    'overwriteIgnore' => false,
                    'commit' => false,
                    'autoStash' => false,
                    'edit' => false,
                    'gpgSign' => false,
                    'log' => false,
                    'fastForward' => 'no',
                    'names' => ['issue-42', 'issue-43'],
                ],
            ],
            'all-in-one-string' => [
                'expected' => [
                    'git',
                    'merge',
                    '--file=my-file-01',
                    '-m', 'my-message-01',
                    '--into-name=my-into-name-01',
                    '--cleanup=strip',
                    '--gpg-sign=my-key-01',
                    '--log=2',
                    '--ff-only',
                    '--strategy=octopus',
                    '--strategy=ort',
                    '--strategy-option=my-true',
                    '--strategy-option=my-int=42',
                    '--strategy-option=my-float=42.56',
                    '--strategy-option=my-string=okay',
                    '--strategy=resolve',
                    'issue-42',
                    'issue-43',
                ],
                'properties' => [
                    'file' => 'my-file-01',
                    'message' => 'my-message-01',
                    'intoName' => 'my-into-name-01',
                    'cleanup' => 'strip',
                    'gpgSign' => 'my-key-01',
                    'log' => 2,
                    'fastForward' => 'only',
                    'strategies' => [
                        'ort' => [
                            'weight' => 2,
                            'options' => [
                                'ignore-me' => null,
                                'my-true' => true,
                                'my-int' => 42,
                                'my-float' => 42.56,
                                'my-string' => 'okay',
                            ],
                        ],
                        'octopus' => [
                            'weight' => 1,
                        ],
                        'subtree' => [
                            'enabled' => false,
                        ],
                        'resolve' => [],
                    ],
                    'names' => ['issue-42', 'issue-43'],
                ],
            ],
        ];
    }
}
