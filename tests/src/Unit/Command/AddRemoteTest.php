<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\AddRemote;

#[CoversClass(AddRemote::class)]
#[Group('command-git-remote')]
class AddRemoteTest extends CommandTestBase
{
    protected function createCommand(): AddRemote
    {
        return new AddRemote();
    }

    /**
     * @return array<string, mixed>
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                ],
            ],
            'with-fetch' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--fetch',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'fetch' => true,
                ],
            ],
            'with-tags' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--tags',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'tags' => true,
                ],
            ],
            'with-mirror' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--mirror=fetch',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'mirror' => 'fetch',
                ],
            ],
            'with-master' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--master=main',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'master' => 'main',
                ],
            ],
            'with-track' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--track=main',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'track' => ['main'],
                ],
            ],
            'with-multiple-track' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--track=main',
                    '--track=develop',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'track' => ['main', 'develop'],
                ],
            ],
            'with-all-options' => [
                'expected' => [
                    'git',
                    'remote',
                    'add',
                    '--fetch',
                    '--mirror=fetch',
                    '--tags',
                    '--master=main',
                    '--no-track',
                    '--track=main',
                    '--track=develop',
                    'origin',
                    'https://github.com/example/repo.git',
                ],
                'properties' => [
                    'name' => 'origin',
                    'url' => 'https://github.com/example/repo.git',
                    'fetch' => true,
                    'tags' => true,
                    'mirror' => 'fetch',
                    'master' => 'main',
                    'track' => [
                        false,
                        'main',
                        'develop',
                    ],
                ],
            ],
        ];
    }

    #[Test]
    public function testExecute(): void
    {
        $processOutcomes = [
            [],
        ];
        $processFactory = $this->createProcessFactory($processOutcomes);

        $command = $this->createCommand();
        $command->setProcessFactory($processFactory);

        $result = $command->execute();
        static::assertNull($result->artifacts);
    }
}
