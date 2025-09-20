<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SetRemoteFetchUrl;

#[CoversClass(SetRemoteFetchUrl::class)]
#[Group('command-git-remote')]
class SetRemoteFetchUrlTest extends CommandTestBase
{

    protected function createCommand(): SetRemoteFetchUrl
    {
        return new SetRemoteFetchUrl();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-url',
                    'upstream',
                    '/dev/null/fetch-new.git',
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                    'url' => '/dev/null/fetch-new.git',
                ],
            ],
            'basic with oldUrl' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-url',
                    'upstream',
                    '/dev/null/fetch-new.git',
                    '/dev/null/fetch-old.git',
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                    'url' => '/dev/null/fetch-new.git',
                    'oldUrl' => '/dev/null/fetch-old.git',
                ],
            ],
        ];
    }
}
