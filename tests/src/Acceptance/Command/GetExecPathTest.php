<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetExecPath;
use Sweetchuck\Git\OutcomeParser\LastLineParser;

#[CoversClass(GetExecPath::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(LastLineParser::class)]
#[Group('command-git')]
class GetExecPathTest extends CommandTestBase
{

    public function testExecute(): void
    {
        $command = new GetExecPath();
        $result = $command->execute();

        static::assertIsArray($result->artifacts);
        static::assertArrayHasKey('execPath', $result->artifacts);
        static::assertMatchesRegularExpression(
            // @todo Depends on the current platform.
            '@/git(-core)?$@',
            $result->artifacts['execPath'],
        );
    }
}
