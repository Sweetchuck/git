<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CheckIgnore;

#[CoversClass(CheckIgnore::class)]
#[Group('command-git-check-ignore')]
class CheckIgnoreTest extends CommandTestBase
{

    protected function createCommand(): CheckIgnore
    {
        return new CheckIgnore();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic' => [
                [
                    'git',
                    'check-ignore',
                    '--verbose',
                    '--non-matching',
                    '--',
                    'a.txt',
                    'b.md',
                ],
                [
                    'paths' => [
                        'a.txt',
                        'b.md',
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
            'basic exitCode 2' => [
                'expected' => [
                    'artifacts' => null,
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 2,
                        'stdOutput' => "\n",
                    ],
                ],
            ],
            'basic empty' => [
                'expected' => [
                    'artifacts' => [
                        'matching' => [],
                        'nonMatching' => [],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => "\n",
                    ],
                ],
            ],
            'basic - not empty' => [
                'expected' => [
                    'artifacts' => [
                        'matching' => [
                            'a.txt' => [
                                [
                                    'filePath' => '.gitignore',
                                    'lineNumber' => 1,
                                    'pattern' => '*.txt',
                                ],
                            ],
                        ],
                        'nonMatching' => [
                            'b.txt',
                        ],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode(
                            "\n",
                            [
                                ".gitignore:1:*.txt\ta.txt",
                                "::\tb.txt",
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
