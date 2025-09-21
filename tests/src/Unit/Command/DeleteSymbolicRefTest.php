<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\DeleteSymbolicRef;

#[CoversClass(DeleteSymbolicRef::class)]
#[Group('command-git-symbolic-ref')]
class DeleteSymbolicRefTest extends CommandTestBase
{

    protected function createCommand(): DeleteSymbolicRef
    {
        return new DeleteSymbolicRef();
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
                    '--delete',
                    'refs/heads/alias',
                ],
                [
                    'name' => 'refs/heads/alias',
                ],
            ],
        ];
    }
}
