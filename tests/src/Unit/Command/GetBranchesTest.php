<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\GetBranches;
use Sweetchuck\Git\Tests\Helper\DummyUniqueIdGenerator;

#[CoversClass(GetBranches::class)]
#[Group('command-git-branch')]
class GetBranchesTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        $uniqueIdGenerator = new DummyUniqueIdGenerator();
        $command = new GetBranches();
        $command->getFormatHandler()->setUniqueIdGenerator($uniqueIdGenerator);

        return $command;
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        $defaultFormat = '--format=';
        $defaultFormat .= implode(
            'ß',
            [
                'refName %(refname:strip=0)',
                'upstream %(upstream:strip=0)',
                'track %(upstream:track)',
                'push %(push:strip=0)',
                'isCurrentBranch %(HEAD)',
            ],
        );
        $defaultFormat .= 'ä';

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [],
            ],
            'color-null' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'color' => null,
                ],
            ],
            'color-true' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--color',
                    $defaultFormat,
                ],
                'properties' => [
                    'color' => true,
                ],
            ],
            'color-false' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--no-color',
                    $defaultFormat,
                ],
                'properties' => [
                    'color' => false,
                ],
            ],
            'color-string-always' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--color=always',
                    $defaultFormat,
                ],
                'properties' => [
                    'color' => 'always',
                ],
            ],
            'color-string-auto' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--color=auto',
                    $defaultFormat,
                ],
                'properties' => [
                    'color' => 'auto',
                ],
            ],
            'color-string-never' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--color=never',
                    $defaultFormat,
                ],
                'properties' => [
                    'color' => 'never',
                ],
            ],
            'all-null' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'all' => null,
                ],
            ],
            'all-true' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--all',
                    $defaultFormat,
                ],
                'properties' => [
                    'all' => true,
                ],
            ],
            'merged-empty' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'merged' => [],
                ],
            ],
            'merged-multiple' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--merged=a',
                    '--no-merged=b',
                    '--merged=d',
                    '--no-merged=e',
                    $defaultFormat,
                ],
                'properties' => [
                    'merged' => [
                        'a' => true,
                        'b' => false,
                        'c' => null,
                        'd' => true,
                        'e' => false,
                    ],
                ],
            ],
            'contains-empty' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'contains' => [],
                ],
            ],
            'contains-both' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--no-contains=h1',
                    '--contains=h2',
                    '--no-contains=h3',
                    '--contains=h4',
                    $defaultFormat,
                ],
                'properties' => [
                    'contains' => [
                        'h1' => false,
                        'h2' => true,
                        'h3' => false,
                        'h4' => true,
                    ],
                ],
            ],
            'pointsAt-null' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'pointsAt' => null,
                ],
            ],
            'pointsAt-false' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--no-points-at',
                    $defaultFormat,
                ],
                'properties' => [
                    'pointsAt' => false,
                ],
            ],
            'pointsAt-value' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--points-at=my-hash',
                    $defaultFormat,
                ],
                'properties' => [
                    'pointsAt' => 'my-hash',
                ],
            ],
            'remotes-null' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'remotes' => null,
                ],
            ],
            'remotes-true' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--remotes',
                    $defaultFormat,
                ],
                'properties' => [
                    'remotes' => true,
                ],
            ],
            'sort-empty' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'sort' => [],
                ],
            ],
            'sort-multi' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--sort=a',
                    '--sort=c',
                    $defaultFormat,
                ],
                'properties' => [
                    'sort' => [
                        'a' => true,
                        'b' => false,
                        'c' => true,
                    ],
                ],
            ],
            'ignoreCase-null' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'ignoreCase' => null,
                ],
            ],
            'ignoreCase-true' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    '--ignore-case',
                    $defaultFormat,
                ],
                'properties' => [
                    'ignoreCase' => true,
                ],
            ],
            'paths-empty' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [
                    'paths' => [],
                ],
            ],
            'paths-array<string, bool>' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $defaultFormat,
                    '--',
                    'a',
                    'c',
                ],
                'properties' => [
                    'paths' => [
                        'a' => true,
                        'b' => false,
                        'c' => true,
                    ],
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
            'basic' => [
                'expected' => [
                    'artifacts' => [
                        'branches' => [
                            'refs/heads/1.x' => [
                                'isCurrentBranch' => true,
                                'push' => 'refs/remotes/upstream/1.x',
                                'push.short' => 'upstream/1.x',
                                'refName' => 'refs/heads/1.x',
                                'refName.short' => '1.x',
                                'track' => '[ahead 1, behind 1]',
                                'track.ahead' => 1,
                                'track.behind' => 1,
                                'track.gone' => false,
                                'upstream' => 'refs/remotes/upstream/1.x',
                                'upstream.short' => 'upstream/1.x',
                            ],
                        ],
                        'currentBranch' => 'refs/heads/1.x',
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                // phpcs:ignore
                                'refName refs/heads/1.xßupstream refs/remotes/upstream/1.xßtrack [ahead 1, behind 1]ßpush refs/remotes/upstream/1.xßisCurrentBranch *ä',
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
