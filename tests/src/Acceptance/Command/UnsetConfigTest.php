<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\UnsetConfig;
use Sweetchuck\Git\Command\GetConfigSingle;

#[CoversClass(UnsetConfig::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-config')]
class UnsetConfigTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initSteps = [
            [
                'type' => 'exec',
                'command' => <<<'SHELL'
                    cd {{ dirSafe }} \
                    && git init \
                    && git config --local g1.k1 'g01-k01-v01' \
                    && git config --local g1.k2 'g01-k02-v01' \
                    && git config --local --type 'bool' g2.k1 true
                    SHELL,
            ],
        ];

        return [
            'basic unset' => [
                'expected' => [
                    'before' => [
                        'exists' => true,
                        'value' => 'g01-k01-v01',
                    ],
                    'after' => [
                        'exists' => false,
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configScope' => [
                        'local' => true,
                    ],
                    'configName' => 'g1.k1',
                ],
            ],
            'unset with value pattern' => [
                'expected' => [
                    'before' => [
                        'exists' => true,
                        'value' => 'g01-k01-v01',
                    ],
                    'after' => [
                        'exists' => false,
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configScope' => [
                        'local' => true,
                    ],
                    'valueState' => true,
                    'valuePattern' => 'g01-k01-v01',
                    'configName' => 'g1.k1',
                ],
            ],
            'unset with fixed value' => [
                'expected' => [
                    'before' => [
                        'exists' => true,
                        'value' => 'g01-k01-v01',
                    ],
                    'after' => [
                        'exists' => false,
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configScope' => [
                        'local' => true,
                    ],
                    'fixedValue' => true,
                    'valueState' => true,
                    'valuePattern' => 'g01-k01-v01',
                    'configName' => 'g1.k1',
                ],
            ],
            'unset non-existent value' => [
                'expected' => [
                    'before' => [
                        'exists' => false,
                    ],
                    'after' => [
                        'exists' => false,
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configScope' => [
                        'local' => true,
                    ],
                    'configName' => 'non.existent',
                ],
            ],
            'unset with wrong value pattern' => [
                'expected' => [
                    'before' => [
                        'exists' => true,
                        'value' => 'g01-k01-v01',
                    ],
                    'after' => [
                        'exists' => true,
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configScope' => [
                        'local' => true,
                    ],
                    'valueState' => true,
                    'valuePattern' => 'g01-k01-vNope',
                    'configName' => 'g1.k1',
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<mixed> $initSteps
     * @param array<string, mixed> $properties
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(
        array $expected,
        array $initSteps,
        array $properties = [],
    ): void {
        $projectDir = $this->createTempDirectory();
        $properties['workingDirectory'] = $projectDir;
        $this->executeSteps($projectDir, $initSteps);

        static::assertConfigValue(
            $expected['before'] ?? [],
            $projectDir,
            $properties['configName'],
            'before',
        );

        $unsetCommand = new UnsetConfig();
        $unsetCommand->setProperties($properties);
        $unsetResult = $unsetCommand->execute();
        static::assertNull($unsetResult->artifacts);

        static::assertConfigValue(
            $expected['after'] ?? [],
            $projectDir,
            $properties['configName'],
            'after',
        );
    }

    /**
     * @param array<string, mixed> $expected
     */
    public static function assertConfigValue(
        array $expected,
        string $projectDir,
        string $configName,
        string $message,
    ): void {
        if (!array_key_exists('exists', $expected)) {
            return;
        }

        $getCommand = new GetConfigSingle();
        $getCommand->setProperties([
            'workingDirectory' => $projectDir,
            'configScope' => [
                'local' => true,
            ],
            'configName' => $configName,
        ]);
        $getResult = $getCommand->execute();
        static::assertSame(
            $expected['exists'],
            isset($getResult->artifacts['name']),
            "$message: existence of config $configName is the expected",
        );

        if (!$expected['exists']) {
            return;
        }

        static::assertSame(
            $configName,
            $getResult->artifacts['name'],
            "$message: config name is the same",
        );

        if (!array_key_exists('value', $expected)) {
            return;
        }

        static::assertSame(
            $expected['value'],
            $getResult->artifacts['value'],
            "$message: config value is the same",
        );
    }
}
