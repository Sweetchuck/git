<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\AddRemotePushUrl;

#[CoversClass(AddRemotePushUrl::class)]
#[Group('command-git-remote')]
class AddRemotePushUrlTest extends CommandTestBase
{

    protected function createCommand(): AddRemotePushUrl
    {
        return new AddRemotePushUrl();
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
                    '--add',
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
