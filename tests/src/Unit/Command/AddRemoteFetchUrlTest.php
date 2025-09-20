<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\AddRemoteFetchUrl;

#[CoversClass(AddRemoteFetchUrl::class)]
#[Group('command-git-remote')]
class AddRemoteFetchUrlTest extends CommandTestBase
{

    protected function createCommand(): AddRemoteFetchUrl
    {
        return new AddRemoteFetchUrl();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-url',
                    '--add',
                    'upstream',
                    '/dev/null/fetch-01.git',
                ],
                'properties' => [
                    'remoteName' => 'upstream',
                    'url' => '/dev/null/fetch-01.git',
                ],
            ],
        ];
    }
}
