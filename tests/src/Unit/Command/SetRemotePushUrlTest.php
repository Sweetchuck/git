<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SetRemotePushUrl;

#[CoversClass(SetRemotePushUrl::class)]
#[Group('command-git-remote')]
class SetRemotePushUrlTest extends CommandTestBase
{

    protected function createCommand(): SetRemotePushUrl
    {
        return new SetRemotePushUrl();
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
                    'upstream',
                    '/dev/null/push-new.git',
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                    'url' => '/dev/null/push-new.git',
                ],
            ],
            'basic with oldUrl' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-url',
                    '--push',
                    'upstream',
                    '/dev/null/push-new.git',
                    '/dev/null/push-old.git',
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                    'url' => '/dev/null/push-new.git',
                    'oldUrl' => '/dev/null/push-old.git',
                ],
            ],
        ];
    }
}
