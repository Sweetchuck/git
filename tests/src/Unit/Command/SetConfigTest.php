<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\SetConfig;

#[CoversClass(SetConfig::class)]
#[Group('command-git-config')]
class SetConfigTest extends CommandTestBase
{
    protected function createCommand(): SetConfig
    {
        return new SetConfig();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic - not valid' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '',
                    '',
                ],
                'properties' => [],
            ],
            'basic - set user.name' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                ],
            ],
            'scope - local' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--local',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'configScope' => [
                        'local' => true,
                    ],
                ],
            ],
            'scope - global' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--global',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'configScope' => [
                        'global' => true,
                    ],
                ],
            ],
            'all option - true' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--all',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'all' => true,
                ],
            ],
            'append option - true' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--append',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'append' => true,
                ],
            ],
            'append option - false' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--no-append',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'append' => false,
                ],
            ],
            'type option - bool' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--type=bool',
                    'core.fileMode',
                    'false',
                ],
                'properties' => [
                    'configName' => 'core.fileMode',
                    'configValue' => 'false',
                    'type' => 'bool',
                ],
            ],
            'file option' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--file=.gitconfig',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'file' => '.gitconfig',
                ],
            ],
            'comment option' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--comment=my comment',
                    'user.name',
                    'Test User',
                ],
                'properties' => [
                    'configName' => 'user.name',
                    'configValue' => 'Test User',
                    'comment' => 'my comment',
                ],
            ],
            'combined options' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--global',
                    '--append',
                    '--type=bool',
                    'core.fileMode',
                    'false',
                ],
                'properties' => [
                    'configName' => 'core.fileMode',
                    'configValue' => 'false',
                    'configScope' => [
                        'global' => true,
                    ],
                    'append' => true,
                    'type' => 'bool',
                ],
            ],
            'combined options with append' => [
                'expected' => [
                    'git',
                    'config',
                    'set',
                    '--global',
                    '--append',
                    'alias.st',
                    ' --short',
                ],
                'properties' => [
                    'configName' => 'alias.st',
                    'configValue' => ' --short',
                    'configScope' => [
                        'global' => true,
                    ],
                    'append' => true,
                ],
            ],
        ];
    }
}
