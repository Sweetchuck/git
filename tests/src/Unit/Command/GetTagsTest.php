<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Sweetchuck\Git\Command\CliCommandInterface;
use Sweetchuck\Git\Command\GetTags;
use Sweetchuck\Git\Tests\Helper\DummyUniqueIdGenerator;

#[CoversClass(GetTags::class)]
#[Group('command-git-tag')]
class GetTagsTest extends CommandTestBase
{

    protected function createCommand(): CliCommandInterface
    {
        $uniqueIdGenerator = new DummyUniqueIdGenerator();
        $command = new GetTags();
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
                '×objectType=%(objecttype)',
                '×objectName=%(objectname)',
                '×taggerDate=%(taggerdate:iso)',
                '×creatorDate=%(creatordate:iso)',
            ],
        );

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'tag',
                    '--list',
                    $expectedFormatDefault,
                ],
                'properties' => [],
            ],
            'color-null' => [
                'expected' => [
                    'git',
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
                    '--list',
                    '--color=never',
                    $expectedFormatDefault,
                ],
                'properties' => [
                    'color' => 'never',
                ],
            ],
            'merged-empty' => [
                'expected' => [
                    'git',
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
                    '--list',
                    '--points-at=my-hash',
                    $expectedFormatDefault,
                ],
                'properties' => [
                    'pointsAt' => 'my-hash',
                ],
            ],
            'sort-empty' => [
                'expected' => [
                    'git',
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                        'tags' => [
                            'refs/tags/v1.2.3' => [
                                'creatorDate' => '2025-03-22 18:00:34 +0100',
                                'objectName' => '1234567890123456789012345678901234567890',
                                'objectType' => 'commit',
                                'refName' => 'refs/tags/v1.2.3',
                                'refName.short' => 'v1.2.3',
                                'taggerDate' => null,
                            ],
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'stdOutput' => implode(
                            '',
                            [
                                '¤refName=refs/tags/v1.2.3',
                                '×objectType=commit',
                                '×objectName=1234567890123456789012345678901234567890',
                                '×taggerDate=',
                                '×creatorDate=2025-03-22 18:00:34 +0100',
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
