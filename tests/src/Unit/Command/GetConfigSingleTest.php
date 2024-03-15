<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\GetConfigSingle;

#[CoversClass(GetConfigSingle::class)]
#[Group('command-git-config')]
class GetConfigSingleTest extends CommandTestBase
{

    protected function createCommand(): GetConfigSingle
    {
        return new GetConfigSingle();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic - default' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    'init.defaultBranch',
                ],
                'properties' => [],
            ],
            'basic - user.name' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                ],
            ],
            'scope - all false' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--no-global',
                    '--no-system',
                    '--no-local',
                    '--no-worktree',
                    'init.defaultBranch',
                ],
                'properties' => [
                    'configScope' => [
                        'global' => false,
                        'system' => false,
                        'local' => false,
                        'worktree' => false,
                    ],
                ],
            ],
            'scope - all true' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--global',
                    '--system',
                    '--local',
                    '--worktree',
                    'init.defaultBranch',
                ],
                'properties' => [
                    'configScope' => [
                        'global' => true,
                        'system' => true,
                        'local' => true,
                        'worktree' => true,
                    ],
                ],
            ],
            'scope - mixed' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--global',
                    '--no-system',
                    '--local',
                    '--no-worktree',
                    'init.defaultBranch',
                ],
                'properties' => [
                    'configScope' => [
                        'global' => true,
                        'system' => false,
                        'local' => true,
                        'worktree' => false,
                    ],
                ],
            ],
            'regexp - true' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--regexp',
                    'init.defaultBranch',
                ],
                'properties' => [
                    'regexp' => true,
                ],
            ],
            'regexp - false' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--no-regexp',
                    'init.defaultBranch',
                ],
                'properties' => [
                    'regexp' => false,
                ],
            ],
            'regexp - null' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    'init.defaultBranch',
                ],
                'properties' => [
                    'regexp' => null,
                ],
            ],
            'regexp - with other options' => [
                'expected' => [
                    'git',
                    'config',
                    'get',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--global',
                    '--no-system',
                    '--regexp',
                    'user.*',
                ],
                'properties' => [
                    'regexp' => true,
                    'configScope' => [
                        'global' => true,
                        'system' => false,
                    ],
                    'configName' => 'user.*',
                ],
            ],
        ];
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesExecute(): array
    {
        return [
            'basic - default' => [
                'expected' => [
                    'artifacts' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'init.defaultBranch',
                        'value.raw' => '1.x',
                        'value' => '1.x',
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => "global\0file:/home/me/.gitconfig\0init.defaultBranch\n1.x\0",
                    ],
                ],
            ],
            'basic - unknown string' => [
                'expected' => [
                    'artifacts' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'user.name',
                        'value.raw' => 'Me',
                        'value' => 'Me',
                    ],
                ],
                'properties' => [
                    'configName' => 'user.name',
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "global\0file:/home/me/.gitconfig\0user.name\nMe\0",
                    ],
                ],
            ],
            'basic - unknown true' => [
                'expected' => [
                    'artifacts' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'core.fileMode',
                        'value.raw' => 'true',
                        'value' => true,
                    ],
                ],
                'properties' => [
                    'configName' => 'core.fileMode',
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "global\0file:/home/me/.gitconfig\0core.fileMode\ntrue\0",
                    ],
                ],
            ],
            'basic - unknown false' => [
                'expected' => [
                    'artifacts' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'core.fileMode',
                        'value.raw' => 'false',
                        'value' => false,
                    ],
                ],
                'properties' => [
                    'configName' => 'core.fileMode',
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "global\0file:/home/me/.gitconfig\0core.fileMode\nfalse\0",
                    ],
                ],
            ],
            'multi-value without --all' => [
                'expected' => [
                    'artifacts' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'foo.bar',
                        'value.raw' => 'b',
                        'value' => 'b',
                    ],
                ],
                'properties' => [
                    'configName' => 'foo.bar',
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                "global\0file:/home/me/.gitconfig\0foo.bar\na\0",
                                "global\0file:/home/me/.gitconfig\0foo.bar\nb\0",
                            ],
                        ),
                    ],
                ],
            ],
            'multi-value with --all' => [
                'expected' => [
                    'artifacts' => [
                        'foo.bar' => [
                            [
                                'scope' => 'global',
                                'origin' => 'file:/home/me/.gitconfig',
                                'name' => 'foo.bar',
                                'value.raw' => 'a',
                                'value' => 'a',
                            ],
                            [
                                'scope' => 'global',
                                'origin' => 'file:/home/me/.gitconfig',
                                'name' => 'foo.bar',
                                'value.raw' => 'b',
                                'value' => 'b',
                            ],
                        ],
                    ],
                ],
                'properties' => [
                    'configName' => 'foo.bar',
                    'all' => true,
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                "global\0file:/home/me/.gitconfig\0foo.bar\na\0",
                                "global\0file:/home/me/.gitconfig\0foo.bar\nb\0",
                            ],
                        ),
                    ],
                ],
            ],
            'regexp - match multiple configs' => [
                'expected' => [
                    'artifacts' => [
                        'user.name' => [
                            [
                                'scope' => 'global',
                                'origin' => 'file:/home/me/.gitconfig',
                                'name' => 'user.name',
                                'value.raw' => 'John Doe',
                                'value' => 'John Doe',
                            ],

                        ],
                        'user.email' => [
                            [
                                'scope' => 'global',
                                'origin' => 'file:/home/me/.gitconfig',
                                'name' => 'user.email',
                                'value.raw' => 'john@example.com',
                                'value' => 'john@example.com',
                            ],
                        ],
                    ],
                ],
                'properties' => [
                    'configName' => 'user.*',
                    'regexp' => true,
                    'all' => true,
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                "global\0file:/home/me/.gitconfig\0user.name\nJohn Doe\0",
                                "global\0file:/home/me/.gitconfig\0user.email\njohn@example.com\0",
                            ],
                        ),
                    ],
                ],
            ],
            'regexp - with all false' => [
                'expected' => [
                    'artifacts' => [
                        'scope' => 'global',
                        'origin' => 'file:/home/me/.gitconfig',
                        'name' => 'user.name',
                        'value.raw' => 'John Doe',
                        'value' => 'John Doe',
                    ],
                ],
                'properties' => [
                    'configName' => 'user.*',
                    'regexp' => true,
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                "global\0file:/home/me/.gitconfig\0user.name\nJohn Doe\0",
                                "global\0file:/home/me/.gitconfig\0user.email\njohn@example.com\0",
                            ],
                        ),
                    ],
                ],
            ],
        ];
    }

    /**
     * @param array<string, mixed> $expected
     * @param array<string, mixed> $properties
     * @param array<array<string, mixed>> $processOutcomes
     */
    #[Test]
    #[DataProvider('casesExecute')]
    public function testExecute(array $expected, array $properties, array $processOutcomes = []): void
    {
        if (!array_key_exists('processFactory', $properties)) {
            $properties['processFactory'] = $this->createProcessFactory($processOutcomes);
        }
        $command = $this->createCommand();
        $command->setProperties($properties);

        $result = $command->execute();
        if (isset($expected['exitCode'])) {
            static::assertSame($expected['exitCode'], $result->process->getExitCode());
        }

        if (isset($expected['stdOutput'])) {
            static::assertSame($expected['stdOutput'], $result->process->getOutput());
        }

        if (isset($expected['stdError'])) {
            static::assertSame($expected['stdError'], $result->process->getErrorOutput());
        }

        if (isset($expected['artifacts'])) {
            static::assertSame($expected['artifacts'], $result->artifacts);
        }
    }
}
