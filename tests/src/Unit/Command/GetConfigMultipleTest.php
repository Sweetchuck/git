<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\GetConfigMultiple;

#[CoversClass(GetConfigMultiple::class)]
#[Group('command-git-config')]
class GetConfigMultipleTest extends CommandTestBase
{

    protected function createCommand(): GetConfigMultiple
    {
        return new GetConfigMultiple();
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
                    'list',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                ],
                'properties' => [],
            ],
            'scope - all false' => [
                'expected' => [
                    'git',
                    'config',
                    'list',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--no-global',
                    '--no-system',
                    '--no-local',
                    '--no-worktree',
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
                    'list',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--global',
                    '--system',
                    '--local',
                    '--worktree',
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
                    'list',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--global',
                    '--no-system',
                    '--local',
                    '--no-worktree',
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
            'file - false' => [
                'expected' => [
                    'git',
                    'config',
                    'list',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--no-file',
                ],
                'properties' => [
                    'file' => false,
                ],
            ],
            'file - path' => [
                'expected' => [
                    'git',
                    'config',
                    'list',
                    '--null',
                    '--show-scope',
                    '--show-origin',
                    '--show-names',
                    '--file=.git/config',
                ],
                'properties' => [
                    'file' => '.git/config',
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
                        'init.defaultBranch' => [
                            'scope' => 'global',
                            'origin' => 'file:/home/me/.gitconfig',
                            'name' => 'init.defaultBranch',
                            'value.raw' => '1.x',
                            'value' => '1.x',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => "global\0file:/home/me/.gitconfig\0init.defaultBranch\n1.x\0",
                    ],
                ],
            ],
            'multiple values' => [
                'expected' => [
                    'artifacts' => [
                        'g1.k1' => [
                            'scope' => 'global',
                            'origin' => 'file:/home/me/.gitconfig',
                            'name' => 'g1.k1',
                            'value.raw' => 'foo',
                            'value' => 'foo',
                        ],
                        'g1.k2' => [
                            'scope' => 'global',
                            'origin' => 'file:/home/me/.gitconfig',
                            'name' => 'g1.k2',
                            'value.raw' => 'true',
                            'value' => true,
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                "global\0file:/home/me/.gitconfig\0g1.k1\nfoo\0",
                                "global\0file:/home/me/.gitconfig\0g1.k2\ntrue\0",
                            ],
                        ),
                    ],
                ],
            ],
            'with file option' => [
                'expected' => [
                    'artifacts' => [
                        'init.defaultBranch' => [
                            'scope' => 'file',
                            'origin' => 'file:.git/config',
                            'name' => 'init.defaultBranch',
                            'value.raw' => 'main',
                            'value' => 'main',
                        ],
                    ],
                ],
                'properties' => [
                    'file' => '.git/config',
                ],
                'processOutcomes' => [
                    [
                        'stdOutput' => "file\0file:.git/config\0init.defaultBranch\nmain\0",
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
