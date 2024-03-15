<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\GetStatus;
use Sweetchuck\Git\OutcomeParser\GetStatusParser;

#[CoversClass(GetStatus::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[CoversClass(GetStatusParser::class)]
class GetStatusTest extends CommandTestBase
{

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'no-commits-clean' => [
                'expected' => [
                    'artifacts' => [],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                ],
                'options' => [],
            ],
            'no-commits-untracked' => [
                'expected' => [
                    'artifacts' => [
                        'README.md' => '??',
                    ],
                ],
                'initSteps' => [
                    [
                        'type' => 'exec',
                        'command' => 'git init {{ dirSafe }}',
                    ],
                    [
                        'type' => 'exec',
                        'command' => 'cd {{ dirSafe }} && touch README.md',
                    ],
                ],
                'options' => [
                    'untrackedFiles' => 'all',
                ],
            ],
            // @todo More cases.
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $options
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $options = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $options['workingDirectory'] = $projectDir;
        $this->initGitRepo($projectDir, $initSteps);

        $command = new GetStatus();
        $command->setoptions($options);
        $result = $command->execute();

        if (array_key_exists('artifacts', $expected)) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
