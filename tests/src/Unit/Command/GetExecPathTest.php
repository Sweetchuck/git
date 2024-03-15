<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\GetExecPath;

#[CoversClass(GetExecPath::class)]
#[Group('command-git')]
class GetExecPathTest extends CommandTestBase
{

    protected function createCommand(): GetExecPath
    {
        return new GetExecPath();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'normal' => [
                'expected' => ['git', '--exec-path'],
                'properties' => [],
            ],
            'custom git executable' => [
                'expected' => ['/usr/local/bin/git', '--exec-path'],
                'properties' => [
                    'gitExecutable' => '/usr/local/bin/git',
                ],
            ],
        ];
    }

    #[Test]
    public function testExecute(): void
    {
        $processOutcomes = [
            [
                'stdOutput' => "/usr/libexec/git\n",
            ],
        ];
        $processFactory = $this->createProcessFactory($processOutcomes);

        $command = $this->createCommand();
        $command->setProcessFactory($processFactory);

        $result = $command->execute();
        static::assertSame(
            [
                'execPath' => '/usr/libexec/git',
            ],
            $result->artifacts,
        );
    }
}
