<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\GetBranches;
use Sweetchuck\Git\Tests\Helper\DummyUniqueIdGenerator;

#[CoversClass(GetBranches::class)]
#[Group('command-git-branch')]
class GetBranchesTest extends CommandTestBase
{

    protected function createCommand(): GetBranches
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
        $expectedFormatDefault = '--format=';
        $expectedFormatDefault .= implode(
            '',
            [
                '¤refName=%(refname:strip=0)',
                '×upstream=%(upstream:strip=0)',
                '×track=%(upstream:track)',
                '×push=%(push:strip=0)',
                '×isCurrentBranch=%(HEAD)',
            ],
        );

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $expectedFormatDefault,
                ],
                'properties' => [],
            ],
            'color-null' => [
                'expected' => [
                    'git',
                    'branch',
                    '--list',
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                    $expectedFormatDefault,
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
                                'track' => '[ahead 1, behind 2]',
                                'track.ahead' => 1,
                                'track.behind' => 2,
                                'track.gone' => false,
                                'upstream' => 'refs/remotes/upstream/1.x',
                                'upstream.short' => 'upstream/1.x',
                                'isDetached' => false,
                            ],
                            'refs/heads/2.x' => [
                                'isCurrentBranch' => false,
                                'push' => 'refs/remotes/upstream/2.x',
                                'push.short' => 'upstream/2.x',
                                'refName' => 'refs/heads/2.x',
                                'refName.short' => '2.x',
                                'track' => '[ahead 3, behind 4]',
                                'track.ahead' => 3,
                                'track.behind' => 4,
                                'track.gone' => false,
                                'upstream' => 'refs/remotes/upstream/2.x',
                                'upstream.short' => 'upstream/2.x',
                                'isDetached' => false,
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
                                '¤refName=refs/heads/1.x',
                                '×upstream=refs/remotes/upstream/1.x',
                                '×track=[ahead 1, behind 2]',
                                '×push=refs/remotes/upstream/1.x',
                                '×isCurrentBranch=*',
                                "\n",
                                "¤refName=refs/heads/2.x",
                                '×upstream=refs/remotes/upstream/2.x',
                                '×track=[ahead 3, behind 4]',
                                '×push=refs/remotes/upstream/2.x',
                                '×isCurrentBranch=',
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
