<?php

declare(strict_types = 1);

namespace Sweetchuck\Git\Tests\Unit\Command;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Sweetchuck\Git\Command\CheckAttr;

#[CoversClass(CheckAttr::class)]
#[Group('command-git-check-attr')]
class CheckAttrTest extends CommandTestBase
{

    protected function createCommand(): CheckAttr
    {
        return new CheckAttr();
    }

    /**
     * {@inheritdoc}
     */
    public static function casesGetCliCommand(): array
    {
        return [
            'basic --all' => [
                [
                    'git',
                    'check-attr',
                    '-z',
                    '--all',
                    '--no-source',
                    '--',
                    'a.txt',
                ],
                [
                    'source' => false,
                    'paths' => [
                        'a.txt' => true,
                        'b.md' => false,
                    ],
                ],
            ],
            'basic attributes' => [
                [
                    'git',
                    'check-attr',
                    '-z',
                    '--cached',
                    '--source=abcdefg',
                    'diff',
                    'eol',
                    '--',
                    'a.txt',
                ],
                [
                    'cached' => true,
                    'source' => 'abcdefg',
                    'attributes' => [
                        'diff' => true,
                        'eol' => true,
                        'whitespace' => false,
                    ],
                    'paths' => [
                        'a.txt' => true,
                        'b.md' => false,
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
                        'stdOutput' => '',
                    ],
                ],
            ],
            'basic empty' => [
                'expected' => [
                    'artifacts' => [
                        'filePaths' => [],
                    ],
                ],
                'properties' => [],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => '',
                    ],
                ],
            ],
            'basic - not empty' => [
                'expected' => [
                    'artifacts' => [
                        'filePaths' => [
                            'a.txt' => [
                                'diff' => 'text',
                                'eol' => 'lf',
                            ],
                            'b.php' => [
                                'diff' => 'php',
                                'eol' => 'lf',
                                'whitespace' => 'tab-in-indent,tabwidth=4',
                            ],
                        ],
                    ],
                ],
                'properties' => [
                    'paths' => [
                        'a.txt' => true,
                        'b.php' => true,
                    ],
                ],
                'processOutcomes' => [
                    [
                        'exitCode' => 0,
                        'stdOutput' => implode(
                            "\x00",
                            [
                                'a.txt', 'diff', 'text',
                                'a.txt', 'eol', 'lf',
                                'b.php', 'diff', 'php',
                                'b.php', 'eol', 'lf',
                                'b.php', 'whitespace', 'tab-in-indent,tabwidth=4',
                                '',
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
