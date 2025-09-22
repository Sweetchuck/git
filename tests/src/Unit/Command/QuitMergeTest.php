<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\QuitMerge;

#[CoversClass(QuitMerge::class)]
#[Group('command-git-merge')]
class QuitMergeTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        return new QuitMerge();
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
                    '--quit',
                ],
                'properties' => [],
            ],
        ];
    }
}
