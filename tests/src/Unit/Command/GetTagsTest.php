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
        $defaultFormat = '--format=';
        $defaultFormat .= implode(
            'ß',
            [
                'refName %(refname:strip=0)',
                'objectType %(objecttype)',
                'objectName %(objectname)',
                'taggerDate %(taggerdate:iso)',
                'creatorDate %(creatordate:iso)',
            ],
        );
        $defaultFormat .= 'ä';

        return [
            'basic' => [
                'expected' => [
                    'git',
                    'tag',
                    '--list',
                    $defaultFormat,
                ],
                'properties' => [],
            ],
            'color-null' => [
                'expected' => [
                    'git',
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
                    '--list',
                    '--color=never',
                    $defaultFormat,
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
                    $defaultFormat,
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
                    '--list',
                    '--points-at=my-hash',
                    $defaultFormat,
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
                    $defaultFormat,
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                    'tag',
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
                                // phpcs:ignore
                                'refName refs/tags/v1.2.3ßobjectType commitßobjectName 1234567890123456789012345678901234567890ßtaggerDate ßcreatorDate 2025-03-22 18:00:34 +0100ä',
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
