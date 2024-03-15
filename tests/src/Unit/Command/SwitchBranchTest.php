<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SwitchBranch;

#[CoversClass(SwitchBranch::class)]
#[Group('command-git-switch')]
class SwitchBranchTest extends CommandTestBase
{

    protected function createCommand(): SwitchBranch
    {
        return new SwitchBranch();
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
                    'switch',
                    'my-new-branch-01',
                ],
                'properties' => [
                    'name' => 'my-new-branch-01',
                ],
            ],
            'create-normal' => [
                'expected' => [
                    'git',
                    'switch',
                    '--create=my-new-branch-01',
                ],
                'properties' => [
                    'createMethod' => 'normal',
                    'name' => 'my-new-branch-01',
                ],
            ],
            'create-force' => [
                'expected' => [
                    'git',
                    'switch',
                    '--force-create=my-new-branch-01',
                ],
                'properties' => [
                    'createMethod' => 'force',
                    'name' => 'my-new-branch-01',
                ],
            ],
            'all-in-one-01' => [
                'expected' => [
                    'git',
                    'switch',
                    '--detach',
                    '--guess',
                    '--discard-changes',
                    '--merge',
                    '--conflict=diff3',
                    '--track=upstream/foo',
                    '--orphan',
                    '--ignore-other-worktrees',
                    '--recurse-submodules',
                    'my-new-branch-01',
                    'my-startPoint-01',
                ],
                'properties' => [
                    'detach' => true,
                    'guess' => true,
                    'discardChanges' => true,
                    'merge' => true,
                    'conflict' => 'diff3',
                    'track' => ['upstream/foo'],
                    'orphan' => true,
                    'ignoreOtherWorktrees' => true,
                    'recurseSubmodules' => [true],
                    'name' => 'my-new-branch-01',
                    'startPoint' => 'my-startPoint-01',
                ],
            ],
        ];
    }
}
