<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\UnsetConfig;

#[CoversClass(UnsetConfig::class)]
#[Group('command-git-config')]
class UnsetConfigTest extends CommandTestBase
{
    protected function createCommand(): UnsetConfig
    {
        return new UnsetConfig();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic - unset user.name' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                ],
            ],
            'scope - local' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--local',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configScope' => [
                        'local' => true,
                    ],
                ],
            ],
            'scope - global' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--global',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configScope' => [
                        'global' => true,
                    ],
                ],
            ],
            'scope - system' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--system',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configScope' => [
                        'system' => true,
                    ],
                ],
            ],
            'scope - worktree' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--worktree',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configScope' => [
                        'worktree' => true,
                    ],
                ],
            ],
            'all option - true' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--all',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'all' => true,
                ],
            ],
            'file option' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--file=.gitconfig',
                    'user.name',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'file' => '.gitconfig',
                ],
            ],
            'fixedValue option - true' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--fixed-value',
                    'user.name',
                ],
                'properties' => [
                    'fixedValue' => true,
                    'configName' => 'user.name',
                ],
            ],
            'valueState and valuePattern' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--value=Test User',
                    'user.name',
                ],
                'properties' => [
                    'valueState' => true,
                    'valuePattern' => 'Test User',
                    'configName' => 'user.name',
                ],
            ],
            'combined options' => [
                'expected' => [
                    'git',
                    'config',
                    'unset',
                    '--global',
                    '--all',
                    '--value=Test User',
                    '--fixed-value',
                    'user.name',
                ],
                'properties' => [
                    'configScope' => [
                        'global' => true,
                    ],
                    'all' => true,
                    'valueState' => true,
                    'valuePattern' => 'Test User',
                    'fixedValue' => true,
                    'configName' => 'user.name',
                ],
            ],
        ];
    }

    #[Test]
    public function testSetProperties(): void
    {
        $command = $this->createCommand();
        $properties = [
            'file' => '.gitconfig',
            'all' => true,
            'configScope' => [
                'global' => true,
            ],
            'valueState' => true,
            'valuePattern' => 'Test User',
            'fixedValue' => true,
            'configName' => 'user.name',
        ];

        $command->setProperties($properties);

        static::assertSame('.gitconfig', $command->getFile());
        static::assertTrue($command->getAll());
        static::assertSame(
            [
                'global' => true,
                'system' => null,
                'local' => null,
                'worktree' => null,
            ],
            $command->getConfigScope(),
        );
        static::assertTrue($command->getValueState());
        static::assertSame('Test User', $command->getValuePattern());
        static::assertTrue($command->getFixedValue());
        static::assertSame('user.name', $command->getConfigName());
    }
}
