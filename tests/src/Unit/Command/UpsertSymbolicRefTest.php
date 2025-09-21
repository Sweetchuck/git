<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\UpsertSymbolicRef;

#[CoversClass(UpsertSymbolicRef::class)]
#[Group('command-git-symbolic-ref')]
class UpsertSymbolicRefTest extends CommandTestBase
{

    protected function createCommand(): UpsertSymbolicRef
    {
        return new UpsertSymbolicRef();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                [
                    'git',
                    'symbolic-ref',
                    'refs/heads/alias',
                    'refs/heads/main',
                ],
                [
                    'name' => 'refs/heads/alias',
                    'pointsTo' => 'refs/heads/main',
                ],
            ],
            'basic with message' => [
                [
                    'git',
                    'symbolic-ref',
                    '-m', 'My reason',
                    'refs/heads/alias',
                    'refs/heads/main',
                ],
                [
                    'message' => 'My reason',
                    'name' => 'refs/heads/alias',
                    'pointsTo' => 'refs/heads/main',
                ],
            ],
        ];
    }
}
