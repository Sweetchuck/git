<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\DeleteRemotePushUrl;

#[CoversClass(DeleteRemotePushUrl::class)]
#[Group('command-git-remote')]
class DeleteRemotePushUrlTest extends CommandTestBase
{

    protected function createCommand(): DeleteRemotePushUrl
    {
        return new DeleteRemotePushUrl();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-url',
                    '--push',
                    '--delete',
                    'upstream',
                    '/dev/null/push-01.git',
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                    'url' => '/dev/null/push-01.git',
                ],
            ],
        ];
    }
}
