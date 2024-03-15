<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetVersion;
use Sweetchuck\Git\OutcomeParser\GetVersionParser;

#[CoversClass(GetVersion::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetVersionParser::class)]
class GetVersionTest extends CommandTestBase
{

    public function testExecute(): void
    {
        $command = new GetVersion();
        $result = $command->execute();

        static::assertIsArray($result->artifacts);
        static::assertArrayHasKey('version', $result->artifacts);
        static::assertMatchesRegularExpression(
            // @todo Depends on the current platform.
            '/^\d+\.\d+\.\d+$/',
            $result->artifacts['version'],
        );
    }
}
