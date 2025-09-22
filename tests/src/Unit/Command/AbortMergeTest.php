<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\AbortMerge;

#[CoversClass(AbortMerge::class)]
#[Group('command-git-merge')]
class AbortMergeTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        return new AbortMerge();
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
                    'merge',
                    '--abort',
                ],
                'properties' => [],
            ],
        ];
    }
}
