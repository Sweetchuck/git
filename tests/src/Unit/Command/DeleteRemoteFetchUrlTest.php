<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\DeleteRemoteFetchUrl;

#[CoversClass(DeleteRemoteFetchUrl::class)]
#[Group('command-git-remote')]
class DeleteRemoteFetchUrlTest extends CommandTestBase
{

    protected function createCommand(): DeleteRemoteFetchUrl
    {
        return new DeleteRemoteFetchUrl();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    'set-url',
                    '--delete',
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
