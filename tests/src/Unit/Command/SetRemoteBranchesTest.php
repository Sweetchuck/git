<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SetRemoteBranches;

#[CoversClass(SetRemoteBranches::class)]
#[Group('command-git-remote')]
class SetRemoteBranchesTest extends CommandTestBase
{

    protected function createCommand(): SetRemoteBranches
    {
        return new SetRemoteBranches();
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
                    'remote',
                    'set-branches',
                    'upstream',
                    'issue-42',
                ],
                'properties' => [
                    'name' => 'upstream',
                    'branch' => 'issue-42',
                ],
            ],
            'with-add-true' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-branches',
                    '--add',
                    'upstream',
                    'issue-42',
                ],
                'properties' => [
                    'add' => true,
                    'name' => 'upstream',
                    'branch' => 'issue-42',
                ],
            ],
            'with-add-false' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-branches',
                    '--no-add',
                    'upstream',
                    'issue-42',
                ],
                'properties' => [
                    'add' => false,
                    'name' => 'upstream',
                    'branch' => 'issue-42',
                ],
            ],
        ];
    }
}
