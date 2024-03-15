<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Acceptance\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandBase;
use Sweetchuck\Git\Command\CommandBase;
use Sweetchuck\Git\Command\SetConfig;
use Sweetchuck\Git\Command\GetConfigSingle;

#[CoversClass(SetConfig::class)]
#[CoversClass(CliCommandBase::class)]
#[CoversClass(CommandBase::class)]
#[Group('command-git-config')]
class SetConfigTest extends CommandTestBase
{
    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        $initSteps = [
            [
                'type' => 'exec',
                'command' => 'cd {{ dirSafe }} && git init',
            ],
        ];

        return [
            'basic' => [
                'expected' => [
                    'getArtifacts' => [
                        'user.name' => [
                            [
                                'scope' => 'local',
                                'origin' => 'file:.git/config',
                                'name' => 'user.name',
                                'value.raw' => 'Test User',
                                'value' => 'Test User',
                            ],
                        ],
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'configScope' => [
                        'local' => true,
                    ],
                ],
            ],
            'boolean value' => [
                'expected' => [
                    'getArtifacts' => [
                        'core.filemode' => [
                            [
                                'scope' => 'local',
                                'origin' => 'file:.git/config',
                                'name' => 'core.filemode',
                                'value.raw' => 'false',
                                'value' => false,
                            ],
                        ],
                    ],
                ],
                'initSteps' => $initSteps,
                'properties' => [
                    'configName' => 'core.fileMode',
                    'configValue' => false,
                    'configScope' => [
                        'local' => true,
                    ],
                ],
            ],
            'append multiple values' => [
                'expected' => [
                    'getArtifacts' => [
                        'foo.bar' => [
                            [
                                'scope' => 'local',
                                'origin' => 'file:.git/config',
                                'name' => 'foo.bar',
                                'value.raw' => 'value1',
                                'value' => 'value1',
                            ],
                            [
                                'scope' => 'local',
                                'origin' => 'file:.git/config',
                                'name' => 'foo.bar',
                                'value.raw' => 'value2',
                                'value' => 'value2',
                            ],
                        ],
                    ],
                ],
                'initSteps' => array_merge(
                    $initSteps,
                    [
                        [
                            'type' => 'exec',
                            'command' => 'cd {{ dirSafe }} && git config --local foo.bar "value1"',
                        ],
                    ],
                ),
                'properties' => [
                    'configScope' => [
                        'local' => true,
                    ],
                    'append' => true,
                    'configName' => 'foo.bar',
                    'configValue' => 'value2',
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

        $setCommand = new SetConfig();
        $setCommand->setProperties($properties);
        $getResult = $setCommand->execute();
        static::assertNull($getResult->artifacts);

        $getCommand = new GetConfigSingle();
        $getCommand->setProperties([
            'workingDirectory' => $projectDir,
            'configName' => $setCommand->getConfigName(),
            'configScope' => [
                'local' => true,
            ],
            'all' => true,
        ]);
        $getResult = $getCommand->execute();
        static::assertSame(0, $getResult->process->getExitCode());

        if (array_key_exists('getArtifacts', $expected)) {
            static::assertSame($expected['getArtifacts'], $getResult->artifacts);
        }
    }
}
