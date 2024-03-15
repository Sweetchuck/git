<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\UpdateRemote;

#[CoversClass(UpdateRemote::class)]
#[Group('command-git-remote')]
class UpdateRemoteTest extends CommandTestBase
{
    protected function createCommand(): UpdateRemote
    {
        return new UpdateRemote();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    'update',
                ],
                'properties' => [],
            ],
            'all in one' => [
                'expected' => [
                    'git',
                    'remote',
                    'update',
                    '--prune',
                    'origin',
                ],
                'properties' => [
                    'prune' => true,
                    'name' => 'origin',
                ],
            ],
        ];
    }
}
