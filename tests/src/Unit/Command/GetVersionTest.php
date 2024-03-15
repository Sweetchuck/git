<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetVersion;

#[CoversClass(GetVersion::class)]
class GetVersionTest extends CommandTestBase
{

    protected function createCommand(): GetVersion
    {
        return new GetVersion();
    }

    public static function casesGetCliCommand(): array
    {
        return [
            'normal' => [
                'expected' => ['git', '--version'],
                'options' => [],
            ],
            'custom git executable' => [
                'expected' => ['/usr/local/bin/git', '--version'],
                'options' => [
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
                'stdOutput' => 'git version 1.2.3',
            ],
        ];
        $processFactory = $this->createProcessFactory($processOutcomes);

        $command = $this->createCommand();
        $command->setProcessFactory($processFactory);

        $result = $command->execute();
        static::assertIsArray($result->artifacts);
        static::assertArrayHasKey('version', $result->artifacts);
        static::assertSame('1.2.3', $result->artifacts['version']);
    }
}
