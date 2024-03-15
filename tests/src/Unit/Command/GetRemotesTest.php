<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\GetRemotes;

#[CoversClass(GetRemotes::class)]
#[Group('command-git-remote')]
class GetRemotesTest extends CommandTestBase
{
    protected function createCommand(): GetRemotes
    {
        return new GetRemotes();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                'expected' => [
                    'git',
                    'remote',
                    '--verbose',
                ],
                'properties' => [],
            ],
            'with custom git executable' => [
                'expected' => [
                    '/usr/local/bin/git',
                    'remote',
                    '--verbose',
                ],
                'properties' => [
                    'gitExecutable' => '/usr/local/bin/git',
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
            'empty' => [
                'expected' => [
                    'artifacts' => [],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => '',
                    ],
                ],
            ],
            'single remote' => [
                'expected' => [
                    'artifacts' => [
                        'origin' => [
                            'fetch' => 'git@github.com:sweetchuck/git-1.x.git',
                            'push' => 'git@github.com:sweetchuck/git-1.x.git',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(PHP_EOL, [
                            'origin  git@github.com:sweetchuck/git-1.x.git (fetch)',
                            'origin  git@github.com:sweetchuck/git-1.x.git (push)',
                            '',
                        ]),
                    ],
                ],
            ],
            'multiple remotes' => [
                'expected' => [
                    'artifacts' => [
                        'origin' => [
                            'fetch' => 'git@github.com:sweetchuck/git-1.x.git',
                            'push' => 'git@github.com:sweetchuck/git-1.x.git',
                        ],
                        'upstream' => [
                            'fetch' => 'https://github.com/sweetchuck/git-1.x.git',
                            'push' => 'https://github.com/sweetchuck/git-1.x.git',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(PHP_EOL, [
                            'origin  git@github.com:sweetchuck/git-1.x.git (fetch)',
                            'origin  git@github.com:sweetchuck/git-1.x.git (push)',
                            'upstream  https://github.com/sweetchuck/git-1.x.git (fetch)',
                            'upstream  https://github.com/sweetchuck/git-1.x.git (push)',
                            '',
                        ]),
                    ],
                ],
            ],
            'different fetch and push URLs' => [
                'expected' => [
                    'artifacts' => [
                        'origin' => [
                            'fetch' => 'git@github.com:sweetchuck/git-1.x.git',
                            'push' => 'git@github.com:user/git-1.x.git',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(PHP_EOL, [
                            'origin  git@github.com:sweetchuck/git-1.x.git (fetch)',
                            'origin  git@github.com:user/git-1.x.git (push)',
                            '',
                        ]),
                    ],
                ],
            ],
            'error case' => [
                'expected' => [
                    'exitCode' => 1,
                    'stdError' => 'fatal: not a git repository',
                    'artifacts' => null,
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 1,
                        'stdOutput' => '',
                        'stdError' => 'fatal: not a git repository',
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
